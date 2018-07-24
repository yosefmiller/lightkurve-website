$(document).ready(function(){
    /* Default settings */
    $.FORM_PREFIX = "/";
    $.FORM_CHECK_INTERVAL = 3000;
    $.validationConfigFields = {};

    /* Form validation */
    $.fn.bootstrapValidator.validators.choiceTextNumber = {
        validate: function(validator, $field, options) {
            var value = $field.val();
            if (value === '') {
                return true;
            }

            // Get validator settings
            var text = options.text,
                min  = options.min,
                max  = options.max;

            // Check if has correct text
            if (value === text) {
                return true;
            }

            // Check if contains number
            if (!$.isNumeric(value)) {
                return {
                    valid: false,
                    message: "Must be either '"+text+"' (recommended) or a number between "+min+" and "+max
                };
            }
            value = parseFloat(value);

            // Check the bounds of the number
            if (value < min || value > max) {
                return {
                    valid: false,
                    message: "Must be either '"+text+"' (recommended) or a number between "+min+" and "+max
                };
            }

            return true;
        }
    };
    $.fn.bootstrapValidator.validators.checkFileColumns = {
        validate: function(validator, $field, options) {
            var file = $field.prop("files")[0];

            // Check if FileReader is available
            if (!window.FileReader) return true;

            // Read text file
            var reader = new FileReader();
            reader.onload = function(e) {
                var customMessage = function (validator, $field, row_num, message) {
                    var message_prefix = "Error on line " + (row_num + 1) + ": ";
                    if (row_num === 0) message_prefix = "";
                    validator.updateMessage($field, "checkFileColumns", message_prefix + message);
                    validator.updateStatus($field, "INVALID", "checkFileColumns");
                };
                var data = e.target.result;
                var rows = data.split("\n");
                var columns = [[], []];
                for (var i = 0; i < rows.length; i++) {
                    // Skip empty rows
                    if (rows[i].length === 0) continue;
                    var row = rows[i].split(" ");

                    // Check for number of columns
                    if (row.length !== 2) {
                        return customMessage(validator, $field, i, "Must be two columns delimited by spaces");
                    }

                    // Check values are numbers
                    if (!$.isNumeric(row[0]) || !$.isNumeric(row[1])) {
                        return customMessage(validator, $field, i, "Must be a valid number");
                    }

                    // Push values to column list
                    columns[0].push(row[0]);
                    columns[1].push(row[1]);
                }

                // Compute column mean values
                var columnMeans = [0, 0];
                var numRows = columns[0].length;
                for (i = 0; i < numRows; i++) {
                    columnMeans[0] += parseFloat(columns[0][i]);
                    columnMeans[1] += parseFloat(columns[1][i]);
                }
                columnMeans[0] = columnMeans[0] / numRows;
                columnMeans[1] = columnMeans[1] / numRows;

                // Detect units
                var units = "";
                if (columnMeans[0] < parseFloat("3e-5")) return customMessage(validator, $field, 0, "Wavelength must be in either: cm, um, nm, Angs, Hz, sec");
                else if ( columnMeans[0] >= parseFloat("3e-5") && columnMeans[0] < parseFloat("3e-1") ) units = "cm";
                else if ( columnMeans[0] >= parseFloat("3e-1") && columnMeans[0] < parseFloat("3e2") ) units = "um";
                else if ( columnMeans[0] >= parseFloat("3e2") && columnMeans[0] < parseFloat("3e4") ) units = "nm";
                else if ( columnMeans[0] >= parseFloat("3e4") && columnMeans[0] < parseFloat("3e5") ) units = "Angs";
                else if ( columnMeans[0] >= parseFloat("3e5")) units = "Hz";

                // Display the dropdowns
                if ($field.is("#starFile")) {
                    $("#starwunits").val(units).trigger("change");
                    $("#starwunits, #starfunits").parent().removeClass("hidden");
                } else if ($field.is("#planFile")) {
                    $("#planwunits").val(units).trigger("change");
                    $("#planwunits, #planfunits").parent().removeClass("hidden");
                }
            };
            reader.readAsText(file);
            return true;
        }
    };
    $.initValidation = function () {
        $('#calculation-form').bootstrapValidator({
            verbose: false,
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: $.validationConfigFields
        }).on("success.form.bv", function (e) {
            e.preventDefault();                         // Prevent form submission
            var $form = $(e.target);                    // The form instance
            var bv = $form.data("bootstrapValidator");  // The validator instance
            if (bv) {
                if (bv.getSubmitButton()) {
                    bv.disableSubmitButtons(false);     // Enable the submit button
                }
            }
            $.newCalculation($form);                      // Submit the form via ajax
            return false;
        });
    };

    /* Tracking user identification */
    $.getCurrentTrackingID = function () {
        // Set fallback id
        var current_id_elem = $("#tracking_id");
        var current_id = parseInt(current_id_elem.val()); // starts at 0

        // Use cookies if enabled
        if ($.isCookiesEnabled()) {
            // Get next id, if cookie set
            var next_id_cookie = document.cookie.match(/(^|;) ?trackingId=([^;]*)(;|$)/);
            if (next_id_cookie) {
                current_id = parseInt(next_id_cookie[2]);
            }
        }

        // Return current tracking id which represents last calculation's id
        return current_id;
    };
    $.setNextTrackingID = function (isReset) {
        var current_id_elem = $("#tracking_id");
        var current_id = $.getCurrentTrackingID();
        var next_id = current_id + 1;
        if (typeof(isReset) === "boolean" && isReset) {
            next_id = 0;
        }

        // Set next id element
        current_id_elem.val(next_id);

        // Use cookies if enabled
        if ($.isCookiesEnabled()) {
            // Set next id cookie
            var expires = new Date();                                       // today
            expires.setTime(expires.getTime() + (60 * 60 * 24 * 365 * 5));  // 5 years
            document.cookie = "trackingId=" + next_id + ";expires=" + expires.toUTCString();
        }

        // Return next tracking id which will be the new calculation's id
        return next_id;
    };
    $.resetTrackingID = function () {
        $.post($.FORM_PREFIX + "clear", function (response) {
            if (response !== "success") { console.log(response); return; }
            $.setNextTrackingID(true);
            $("#calculation-list, #calculation-logs, #calculation-result").addClass("hidden");
            $("#calculation-table").find("tbody").html("");
        });
    };

    /* Initialize the display */
    $.initCalculationList = function () {
        // Define the html for calculation list and results
        var calculation_list_html = '' +
            '<div class="col-md-12"><h2 class="page-header">Calculation Dashboard<a id="calculation-clear-all" href="#" title="Clear All">Clear All</a></h2></div>' +
            '<div class="col-md-12"><table id="calculation-table" class="table table-hover">' +
            '<thead><tr><th>#</th><th>Name</th><th>Date</th><th>Status</th><th>Tools</th></tr></thead>' +
            '<tbody></tbody></table></div>';

        var calculation_result_html = '' +
            '<div class="col-md-12">' +
            '<h2 class="page-header">Calculation Results' +
            '<small style="float: right" id="result-date"></small>' +
            '<small style="float: right; padding-right: 10px" id="result-name"></small>' +
            '</h2>' +
            '</div>' +
            '<div class="col-md-12">' +
            '<h4>Input Data</h4>' +
            '<table id="input-table" class="table table-striped">' +
            '<thead><tr style="text-align: right;"><th>Name</th><th>Value</th></tr></thead><tbody></tbody>' +
            '</table>' +
            '</div>';

        // Create and hide an empty calculation list and results
        var calculation_list_element = $("#calculation-list");
        calculation_list_element.addClass("clearfix hidden").html(calculation_list_html);
        $("#calculation-result").addClass('clearfix hidden').prepend(calculation_result_html);
        $("#calculation-clear-all").click(function (e) {
            e.preventDefault();
            $.resetTrackingID();
            return false;
        });

        // Fetch all calculations
        var latest_id = $.getCurrentTrackingID();
        for (var id = 1; id <= latest_id; id++) {
            $.createListItem(id);
            calculation_list_element.removeClass("hidden");
            $.post($.FORM_PREFIX + "check/" + id, $.handleCalculationResponse);
        }
    };
    $.createListItem = function (id, name, date) {
        if (typeof(name) !== "string") { name = ""; }
        if (typeof(date) !== "string") { date = ""; }
        var table_item_html = '' +
            '<tr class="calculation-running" id="' + id + '">' +
            '<td class="calculation-id">' + id + '</td>' +
            '<td class="calculation-name">' + name + '</td>' +
            '<td class="calculation-date">' + date + '</td>' +
            '<td>' +
            '<div class="status-running-text"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>&nbsp;&nbsp;Running</div>' +
            '<div class="status-cancelled-text"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp;&nbsp;Cancelled</div>' +
            '<div class="status-finished-text"><span class="glyphicon glyphicon-ok-circle"></span>&nbsp;&nbsp;Finished</div>' +
            '<div class="status-error-text"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp;&nbsp;Error</div>' +
            '<div class="status-pending-text"><span class="glyphicon glyphicon-record"></span>&nbsp;&nbsp;Pending</div>' +
            '</td>' +
            '<td class="calculation-tools"><div class="btn-group" role="group" aria-label="...">' +
            '<a class="btn btn-default disabled calculation-view" title="View Results" href="#" role="button"><span class="glyphicon glyphicon-eye-open"></span></a>' +
            // '<a class="btn btn-default disabled calculation-download" title="Download Results" href="#" role="button"><span class="glyphicon glyphicon-download-alt"></span></a>' +
            '<a class="btn btn-default disabled calculation-logs" title="View Log" href="#" role="button"><span class="glyphicon glyphicon-comment"></span></a>' +
            '</div></td>' +
            '</tr>';
        $("#calculation-table").find("tbody").prepend(table_item_html);
    };

    /* Run new calculation */
    $.newCalculation = function (form) {
        // Increment calculation id
        var id = $.setNextTrackingID();

        // Set calculation meta data
        var name = form.find("#calc_name").val();
        var date = $.getDateTimeText();
        form.find("#calc_date").val(date);

        // Add calculation to table
        $.createListItem(id, name, date);

        // Bring list into view
        var calculation_list_element = $("#calculation-list");
        calculation_list_element.removeClass("hidden");
        $('html, body').animate({ scrollTop: calculation_list_element.offset().top }, 500);

        // Store a cookie requesting a user id
        if ($.isCookiesEnabled()) {
            document.cookie = "needcookie=1";
        }

        // Submit a new calculation using AJAX
        $.ajax({
            data: form.serialize(),
            type: 'POST',
            url: $.FORM_PREFIX + "run",
            success: $.handleCalculationResponse,
            error: function() {
                alert('An unexpected error occurred.');
            }
        });
    };
    $.handleCalculationResponse  = function (response) {
        // Determine action
        if (!response.status) { alert("A server error occurred."); return; }

        // Update status
        var tracking_number = response.input.tracking_id.split("_")[2];
        var table_item = $("#calculation-table").find("tr#"+tracking_number);

        if (response.status === "success") {
            table_item.attr("class", "calculation-finished");
            table_item.find(".calculation-name").html(response.input.calc_name);
            table_item.find(".calculation-date").html(response.input.calc_date);
            table_item.find(".calculation-tools a").removeClass("disabled");
            table_item.find(".calculation-view").click($.displayResults);
            table_item.find(".calculation-logs").click($.displayLogs);
        }
        else if (response.status === "running") {
            table_item.attr("class", "calculation-running");
            table_item.find(".calculation-name").html(response.input.calc_name);
            setTimeout(function () {
                $.post($.FORM_PREFIX + "check/"+tracking_number, $.handleCalculationResponse);
            }, $.FORM_CHECK_INTERVAL);
        }
        else if (response.status === "error") {
            table_item.attr("class", "calculation-error");
            table_item.find(".calculation-tools a.calculation-logs").removeClass("disabled");
            table_item.find(".calculation-logs").click($.displayLogs);
            if (response.type === "validation") alert("An error occurred with message:\n"+response.message);
        }

        // Store response
        if (!$.calculation) { $.calculation = []; }
        $.calculation[response.input.tracking_id.split("_")[2]] = response;
    };

    /* Update display with results */
    $.displayResults  = function (e) {
        e.preventDefault();

        // Load response
        var id = this.closest("tr").id;
        var response = $.calculation[id];
        console.log(response);

        // Display result panel and hide log panel
        var calculation_result = $("#calculation-result");
        calculation_result.removeClass('hidden');
        $("#calculation-logs").addClass("hidden");
        $('html, body').animate({ scrollTop: calculation_result.offset().top }, 500);

        // Display calculation general info
        $("#result-name").html(response.input.calc_name);
        $("#result-date").html(response.input.calc_date);

        // Display input data
        var input_table = $("#input-table").find("tbody");
        input_table.html("");
        Object.keys(response.input)
            .sort()
            .forEach(function(name) {
                if (name.indexOf("tracking") !== -1) { return; }
                if (name.indexOf("calc") !== -1) { return; }
                value = response.input[name];
                name = name.replace("_", " ").replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
                input_table.append('<tr><th>'+name+'</th><td>'+value+'</td></tr>');
            });

        //  Plot result
        $.plotResult(response);
        return false;
    };
    $.displayLogs = function (e) {
        e.preventDefault();

        // Load log file
        var id = this.closest("tr").id;
        $.get($.FORM_PREFIX + "logs/" + id, function (file) {
            $("#calculation-logs").html("<div class='well' style='white-space: pre'>"+ file +"</div>");
        });

        // Display log panel and hide result panel
        $("#calculation-logs").removeClass('hidden');
        $("#calculation-result").addClass("hidden");

        return false;
    };

    /* Helper functions */
    $.plotData  = function (plot, layout, output_file_url, yTitle, xList, custom) {
        $.get($.FORM_PREFIX + output_file_url, function (file) {
            var rows = file.split("\n");

            // Parse column titles (first column)
            var columns = [];
            var columnText = rows[0];
            columnText = columnText.trim().split(/\s+/);
            for (var c = 0; c < columnText.length; c++) { columns.push([]); }

            // Add data to columns
            for (var i = 1; i < rows.length; i++) {
                // Skip empty rows
                if (rows[i].length === 0) continue;
                var row = rows[i].trim().split(/\s+/);

                // Push values to column list
                for (var j = 0; j < row.length; j++) {
                    columns[j].push(row[j]);
                }
            }

            var data = [];
            var yData = columns[columnText.indexOf(yTitle)];
            for (var x = 0; x < xList.length; x++) {
                var columnIndex = columnText.indexOf(xList[x]);
                var name = columnText[columnIndex];
                var xData = columns[columnIndex];
                var dataParams = {x: xData, y: yData, name: name, type: "line"};
                data.push($.extend({}, dataParams, custom || {}));
            }

            Plotly.newPlot(plot, data, layout);
        });
    };
    $.isCookiesEnabled  = function () {
        if (navigator.cookieEnabled) return true;
        // set and read cookie
        document.cookie = "cookietest=1";
        var ret = document.cookie.indexOf("cookietest=") !== -1;
        // delete cookie
        document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
        return ret;
    };
    $.getDateTimeText = function () {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var min = today.getMinutes();
        var suffix = "AM";

        if (dd < 10) { dd = '0'+dd; }
        if (mm < 10) { mm = '0'+mm; }
        if (hh >= 12) { suffix = "PM"; }
        if (hh > 12) { hh -= 12; }
        if (min < 10) { min = '0'+min; }

        today = mm + '/' + dd + '/' + yyyy + "  " + hh + ":" + min + " " + suffix;
        return today;
    };

    /* File-browsing buttons */
    $(".niceFileBtn").click(function () {
        $(this).parent().find("input").click().change(function () {
            $(this).parent().find(".btn").css("display", "none");
            $(this).css("display", "block");
        });
    });

    /* Number force significant digits */
    $(".force-num-sig-2").change(function () {
        var oldVal = parseFloat(this.value);
        var newVal = oldVal.toFixed(2);
        if (isNaN(oldVal) || newVal.length < oldVal.toString().length) return;
        this.value = newVal;
    });

    /* Number sliders */
    $(".range-container input[type=text]").on("input", function(){ $(this).closest(".range-container").find("input[type=range]").val($(this).val()); });
    $(".range-container input[type=range]").on("input", function(){ $(this).closest(".range-container").find("input[type=text]").val($(this).val()); });
});