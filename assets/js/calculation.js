$(document).ready(function(){
    /* Default settings */
    $.FORM_PREFIX = $("base").attr("href");
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
    $.clearTrackingID = function (id) {
        // Determine intent
        var clear_url = id ? "clear/" + id : "clear";

        // Clear selected/all calculations files and processes
        $.post($.FORM_PREFIX + clear_url, function (response) {
            // Assert success
            if (response !== "success") { console.log(response); return; }

            // Adjust calculation list accordingly
            if (id) {
                delete $.calculation[id];
                $("#calculation-table").find("tbody tr#" + id).remove();
            } else {
                $.calculation = {};
                $("#calculation-table").find("tbody").html("");
            }

            // Hide list entirely if empty
            if ($.isEmptyObject($.calculation)) {
                $("#calculation-list").addClass("hidden");
            }

            // Hide results and logs
            $("#calculation-logs, #calculation-result").addClass("hidden");

            // Readjust the indexes displayed
            $.updateListIndexes();
        });

    };

    /* Initialize the display */
    $.initCalculationList = function () {
        // Define the html for calculation list and results
        var calculation_list_html = '' +
            '<div class="col-md-12"><h3 class="page-header">Calculation Dashboard<a id="calculation-clear-all" href="#" title="Clear All">Clear All</a></h3></div>' +
            '<div class="col-md-12"><table id="calculation-table" class="table table-hover">' +
            '<thead><tr><th>#</th><th>Name</th><th>Date</th><th>Status</th><th>Tools</th></tr></thead>' +
            '<tbody></tbody></table></div>';

        var calculation_result_html = '' +
            '<div class="col-md-12">' +
            '<h3 class="page-header">Calculation Results' +
            '<small style="float: right" id="result-date"></small>' +
            '<small style="float: right; padding-right: 10px" id="result-name"></small>' +
            '</h3>' +
            '</div>';

        var calculation_input_html = '' +
            '<div class="col-md-6 table-responsive">' +
            '<h4>Input Values</h4>' +
            '<table id="input-table" class="table table-striped">' +
            '<thead><tr style="text-align: right;"><th>Name</th><th>Value</th></tr></thead><tbody></tbody>' +
            '</table>' +
            '</div>';

        // Create and hide an empty calculation list and results
        var calculation_list_element = $("#calculation-list");
        var calculation_result_element = $("#calculation-result");
        calculation_list_element.html(calculation_list_html).addClass("row hidden");
        calculation_result_element.prepend(calculation_result_html).addClass('row hidden');
        calculation_result_element.append(calculation_input_html);

        $("#calculation-clear-all").click(function (e) {
            e.preventDefault();
            $.clearTrackingID();
            return false;
        });

        // Fetch all calculations
        $.get($.FORM_PREFIX + "list", function (response) {
            // Determine status
            if (!response.list) { console.log("Error! Cannot initialize list!"); return; }

            // Iterate through calculation list
            var calculation_list = response.list;
            $.each(calculation_list, function (key, status) { $.handleCalculationResponse(status, true); });
        });
    };
    $.createListItem = function (id, name, date) {
        if (typeof(name) !== "string") { name = ""; }
        if (typeof(date) !== "string") { date = ""; }
        var table_item_html = '' +
            '<tr class="calculation-running" id="' + id + '">' +
            '<td class="calculation-id"></td>' +
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
            '<a class="btn btn-default calculation-delete" title="Delete" href="#" role="button"><span class="glyphicon glyphicon-trash"></span></a>' +
            '</div></td>' +
            '</tr>';
        $("#calculation-table").find("tbody").prepend(table_item_html);
        $(".calculation-delete").click($.deleteCalculation);
    };
    $.updateListIndexes = function () {
        if (!$.calculation) return;
        $.each($.calculation, function (index, val) {
            var el = $("#calculation-table tr#"+index);
            // el.find(".calculation-id").html("<span title='Calculation ID: "+index+"'>"+(el.index()+1)+"</span>");
            el.find(".calculation-id").html("<span class='text-muted small'>"+index+"</span>");
        })
    };

    /* Run new calculation */
    $.newCalculation = function (form) {
        // Increment calculation id
        var temp_id = Math.random().toString(36).substr(2, 5);
        $("#tracking_id").val(temp_id);

        // Set calculation meta data
        var name = form.find("#calc_name").val();
        var date = $.getDateTimeText();
        form.find("#calc_date").val(date);

        // Add calculation to table
        $.createListItem(temp_id, name, date);

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
    $.handleCalculationResponse  = function (response, initial) {
        // Determine action
        if (!response.status) { alert("A server error occurred when handling calculation status."); console.log(response); return; }
        if (typeof initial !== "boolean") { initial = false; }

        // Get tracking number
        var tracking_number = response.input.tracking_id.split("_").pop();
        var calculation_table = $("#calculation-table");
        var calculation_list  = $("#calculation-list");
        if (response.temp_id) {
            // Change temporary ID into permanent ID
            calculation_table.find("tr#"+response.temp_id).attr("id", tracking_number);
        }

        // Find table list item
        var table_item = calculation_table.find("tr#"+tracking_number);
        if (!table_item.length) {
            // Create new list item on page reload
            if (!initial) { console.log("Skipping deleted calculation."); return; }
            $.createListItem(tracking_number);
            calculation_list.removeClass("hidden");
            table_item = calculation_table.find("tr#"+tracking_number);
        }
        var is_active = table_item.hasClass("info");

        // Update status
        if (response.status === "success") {
            table_item.attr("class", "calculation-finished");
            table_item.find(".calculation-name").html(response.input.calc_name);
            table_item.find(".calculation-date").html(response.input.calc_date);
            table_item.find(".calculation-tools a").removeClass("disabled");
            table_item.find(".calculation-view").click($.displayResults);
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
            if (response.type === "validation") alert("An error occurred with message:\n"+response.message);
        }

        // Update active status
        if (is_active) table_item.addClass("info");

        // Display logs button
        table_item.find(".calculation-tools a.calculation-logs").removeClass("disabled");
        table_item.find(".calculation-logs").click($.displayLogs);

        // Store response
        if (!$.calculation)   { $.calculation = {}; }
        if (response.temp_id) { delete $.calculation[response.temp_id]; }
        $.calculation[tracking_number] = response;
        $.updateListIndexes();
    };

    /* Update display with results */
    $.displayResults  = function (e) {
        e.preventDefault();

        // Load response
        var tr = this.closest("tr");
        var response = $.calculation[tr.id];
        console.log(response);

        // Display active status
        $("#calculation-table tr").removeClass("info");
        $(tr).addClass("info");

        // Display result panel and hide log panel
        var calculation_result = $("#calculation-result");
        calculation_result.removeClass('hidden');
        $("#calculation-logs").addClass("hidden");
        $('html, body').animate({ scrollTop: calculation_result.offset().top }, 500);

        // Display calculation general info
        $("#result-name").html(response.input.calc_name);
        $("#result-date").html(response.input.calc_date);

        // Display input data
        var input_order = $.FORM_INPUT_ORDER || Object.keys(response.input).sort();
        var input_table = $("#input-table").find("tbody");
        input_table.html("");
        input_order.forEach(function(name) {
                if (name.indexOf("tracking_id") !== -1) { return; }
                if (name.indexOf("calc") !== -1) { return; }
                value = response.input[name];
                if (value.length === 0) return;
                name = name.replace("_", " ").replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
                input_table.append('<tr><th>'+name+'</th><td>'+value+'</td></tr>');
            });

        // Display output data
        var output_order = $.FORM_OUTPUT_ORDER || Object.keys(response.output).sort();
        var output_table = $("#output-table").find("tbody");
        output_table.html("");
        output_order.forEach(function(name) {
                var value = response.output[name];
                var title = "";
                if (typeof value == "object") { title = value[1]; value = value[0]; }
                if (value.length === 0) return;
                name = name.replace("_", " ").replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
                output_table.append('<tr title="' + title + '"><th>'+name+'</th><td>'+value+'</td></tr>');
            });

        //  Plot result
        if (typeof $.plotResult === "function") $.plotResult(response);
        return false;
    };
    $.displayLogs = function (e) {
        e.preventDefault();

        // Display active status
        var tr = this.closest("tr");
        $("#calculation-table tr").removeClass("info");
        $(tr).addClass("info");

        // Load log file
        $.get($.FORM_PREFIX + "logs/" + tr.id, function (file) {
            file = file.replace(/^``(.*)$/mg, "<span class='text-primary'>$1</span>");
            $("#calculation-logs").html("<div class='well' style='white-space: pre'></div>");
            $("#calculation-logs .well").html(file);
        });

        // Display log panel and hide result panel
        $("#calculation-logs").removeClass('hidden');
        $("#calculation-result").addClass("hidden");

        return false;
    };
    $.deleteCalculation = function (e) {
        e.preventDefault();

        // Kill the specified id
        var id = this.closest("tr").id;
        $.clearTrackingID(id);

        return false;
    };

    /***** HELPER FUNCTIONS *****/

    /**
     *  Plot data using Plotly from a datafile:
     *  - plot:           (dom element) div to host plot
     *  - layout:         (object) passed directly to plotly for layout/title/axis/legend/shapes
     *  - outputFileUrl:  (url) plaintext file in which each column (delineated by white-space) contains the column-name followed by the values
     *  - yNames:         (string or array) name of column containing y values
     *  - xNames:         (array) name of column containing x values
     *  - customData:     (object, optional) added to each data entry
     *  - customDataList: (array of objects, optional) added to each corresponding data entry
     **/
    $.plotData = function (plot, layout, outputFileUrl, yNames, xNames, customData, customDataList) {
        $.get($.FORM_PREFIX + outputFileUrl, function (file) {
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
                    var value = row[j].indexOf(',') > -1 ? row[j].split(',') : row[j];
                    columns[j].push(value);
                }
            }

            var data = [];
            for (var x = 0; x < xNames.length; x++) {
                var columnNameX = columnText.indexOf(xNames[x]);
                var columnNameY = columnText.indexOf($.isArray(yNames) ? yNames[x] : yNames);
                var name = columnText[columnNameX];
                var xData = columns[columnNameX];
                var yData = columns[columnNameY];
                var dataParams = {x: xData, y: yData, name: name, type: "line"};
                if (typeof(xData[0]) === "object") dataParams = {z: xData, name: name, type: "heatmap"};
                data.push($.extend(dataParams, customData || {}, customDataList ? customDataList[x] : {}));
            }

            Plotly.newPlot(plot, data, layout);
        });
    };
    $.isCookiesEnabled  = function () {
        return false;
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