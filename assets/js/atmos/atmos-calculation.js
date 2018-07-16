$(document).ready(function(){
	/***** FORM ELEMENTS *****/
	/* Nice block level radio selection */
	$(".radio-chooser-content").click(function () {
		$(".radio-chooser-item").removeClass("radio-chooser-selected");
		$(this).parent().addClass("radio-chooser-selected");
		switch ( $(this).find("input").attr("id") ) {
			case "stellarModelPhoenix":
				$(".upload-stellar-section").addClass("hidden");
				$(".phoenix-section").removeClass("hidden");
				break;
			case "stellarModelUser":
				$(".phoenix-section").addClass("hidden");
				$(".upload-stellar-section").removeClass("hidden");
				break;
			case "planetModelConstant":
				$(".upload-planet-section").addClass("hidden");
				$(".constant-transit-section").removeClass("hidden");
				break;
			case "planetModelUser":
				$(".constant-transit-section").addClass("hidden");
				$(".upload-planet-section").removeClass("hidden");
				break;
			case "noiseModelConstant":
				$(".upload-noise-section").addClass("hidden");
				$(".constant-noise-section").removeClass("hidden");
				break;
			case "noiseModelUser":
				$(".constant-noise-section").addClass("hidden");
				$(".upload-noise-section").removeClass("hidden");
				break;
		}
	});
	
	/* File Browsing Button */
	$(".niceFileBtn").click(function () {
		$(this).parent().find("input").click().change(function () {
			$(this).parent().find(".btn").css("display", "none");
			$(this).css("display", "block");
		});
	});
	
	/* Instrument Selection */
	$("#instrument").change(function () {
		$("#instrument-mode-section").removeClass("hidden");
		$("#MIRI, #NIRSpec, #NIRISS, #NIRCam").addClass("hidden");
		var selopt = $("#instrument").val();
		switch (selopt) {
			case "MIRI":
				$("#MIRI").removeClass("hidden");
				break;
			case "NIRSpec":
				$("#NIRSpec").removeClass("hidden");
				break;
			case "NIRISS":
				$("#NIRISS").removeClass("hidden");
				break;
			case "NIRCam":
				$("#NIRCam").removeClass("hidden");
				break;
		}
	});
	
	/* Constant Planet Units */
	$("#constplanfunits").change(function () {
		$(".primary-transit-section, .secondary-transit-section").addClass("hidden");
		var selopt = $("#constplanfunits").val();
		switch (selopt) {
			case "primary":
				$(".primary-transit-section").removeClass("hidden");
				break;
			case "secondary":
				$(".secondary-transit-section").removeClass("hidden");
				break;
			case "phase":
				break;
		}
	});
	
	/* Planet Template */
	$("#planet_template").change(function () {
		$("#planet_options").removeClass("hidden");
	});
	$("#isVolcanism").change(function () {
		if ($(this).is(":checked")) { $("#volcanism-section").removeClass("hidden"); }
		else { $("#volcanism-section").addClass("hidden"); }
	});
	$("#isMethane").change(function () {
		if ($(this).is(":checked")) { $("#methane-section").removeClass("hidden"); }
		else { $("#methane-section").addClass("hidden"); }
	});
	
	/* Number slider */
	$(".range-container input[type=text]").on("input", function(){ $(this).closest(".range-container").find("input[type=range]").val($(this).val()); });
	$(".range-container input[type=range]").on("input", function(){ $(this).closest(".range-container").find("input[type=text]").val($(this).val()); });
	/***** END FORM ELEMENTS *****/
	
	/***** FORM VALIDATION *****/
	var validationConfigFields = {
		calc_name: {
			validators: {
				notEmpty: {
					message: 'Calculation name is required'
				}
			}
		},
		planet_template: {
			validators: {
				notEmpty: {
					message: 'Template selection is required'
				}
			}
		},
		surface_gravity: {
			validators: {
				notEmpty: {
					message: 'Surface gravity is required'
				},
				numeric: {
					message: 'Surface gravity must be a number'
				},
				between: {
					min: 0.75,
					max: 1.5,
					message: 'Surface gravity must be between 0.75g and 1.5g'
				}
			}
		},
		planet_radius: {
			validators: {
				notEmpty: {
					message: 'Planet radius is required'
				},
				numeric: {
					message: 'Planet radius must be a number'
				},
				between: {
					min: 0.5,
					max: 2.0,
					message: 'Planet radius must be between 0.5R and 2.0R'
				}
			}
		}
	};
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
	$('#calculation-form').bootstrapValidator({
		verbose: false,
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: validationConfigFields
	}).on("success.form.bv", function (e) {
		e.preventDefault();                         // Prevent form submission
		var $form = $(e.target);                    // The form instance
		var bv = $form.data("bootstrapValidator");  // The validator instance
		if (bv) {
			if (bv.getSubmitButton()) {
				bv.disableSubmitButtons(false);     // Enable the submit button
			}
		}
		newCalculation($form);                      // Submit the form via ajax
		return false;
	});
	/***** END FORM VALIDATION *****/
	
	$("#calculation-clear-all").click(function (e) {
		e.preventDefault();
		resetTrackingID();
		return false;
	});
	
	/* Form Submission */
	function createListItem(id, name, date) {
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
			'<a class="btn btn-default disabled calculation-view" href="#" role="button"><span class="glyphicon glyphicon-eye-open"></span></a>' +
			'<a class="btn btn-default disabled calculation-download" href="#" role="button"><span class="glyphicon glyphicon-download-alt"></span></a>' +
			'</div></td>' +
			'</tr>';
		$("#calculation-table").find("tbody").prepend(table_item_html);
	}
	function getCurrentTrackingID() {
		// Set fallback id
		var current_id_elem = $("#tracking_id");
		var current_id = parseInt(current_id_elem.val()); // starts at 0
		
		// Use cookies if enabled
		if (isCookiesEnabled()) {
			// Get next id, if cookie set
			var next_id_cookie = document.cookie.match(/(^|;) ?trackingId=([^;]*)(;|$)/);
			if (next_id_cookie) {
				current_id = parseInt(next_id_cookie[2]);
			}
		}
		
		// Return current tracking id which represents last calculation's id
		return current_id;
	}
	function setNextTrackingID(isReset) {
		var current_id_elem = $("#tracking_id");
		var current_id = getCurrentTrackingID();
		var next_id = current_id + 1;
		if (typeof(isReset) === "boolean" && isReset) {
			next_id = 0;
		}
		
		// Set next id element
		current_id_elem.val(next_id);
		
		// Use cookies if enabled
		if (isCookiesEnabled()) {
			// Set next id cookie
			var expires = new Date();                                       // today
			expires.setTime(expires.getTime() + (60 * 60 * 24 * 365 * 5));  // 5 years
			document.cookie = "trackingId=" + next_id + ";expires=" + expires.toUTCString();
		}
		
		// Return next tracking id which will be the new calculation's id
		return next_id;
	}
	function resetTrackingID() {
		$.post("atmos/clear/all", function (response) {
			if (response !== "success") { console.log(response); return; }
			setNextTrackingID(true);
			$("#calculation-table").find("tbody").html("");
		});
	}
	function loadOldCalculations() {
		var latest_id = getCurrentTrackingID();
		for (var id = 1; id <= latest_id; id++) {
			createListItem(id);
			$("#calculation-list").removeClass("hidden");
			$.post("atmos/check/"+id, handleCalculation);
		}
	}
	function newCalculation(form) {
		// Increment calculation id
		var id = setNextTrackingID();
		
		// Add calculation to table
		var name = form.find("#calc_name").val();
		var date = getDateTimeText();
		form.find("#calc_date").val(date);
		createListItem(id, name, date);
		$("#calculation-list").removeClass("hidden");
		$('html, body').animate({ scrollTop: $("#calculation-list").offset().top }, 500);
		
		// Store a cookie requesting a user id
		if (isCookiesEnabled()) {
			document.cookie = "needcookie=1";
		}
		
		// Submit a new calculation using AJAX
		$.ajax({
			data: form.serialize(),
			type: 'POST',
			url: form.attr("action"),
			success: handleCalculation,
			error: function() {
				alert('An unexpected error occurred.');
			}
		});
	}
	function handleCalculation (response) {
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
			table_item.find(".calculation-view").click(displayCalculation);
		}
		else if (response.status === "running") {
			table_item.attr("class", "calculation-running");
			table_item.find(".calculation-name").html(response.input.calc_name);
			setTimeout(function () {
				$.post("atmos/check/"+tracking_number, handleCalculation);
			}, 2000);
		}
		else if (response.status === "error") {
			table_item.attr("class", "calculation-error");
			alert("An error occurred with message:\n"+response.message);
		}
		
		// Store response
		if (!$.calculation) { $.calculation = []; }
		$.calculation[response.input.tracking_id.split("_")[2]] = response;
	}
	function displayCalculation (e) {
		e.preventDefault();
		
		// Load response
		var id = this.closest("tr").id;
		var response = $.calculation[id];
		console.log(response);
		
		// Display result panel
		$("#calculation-result").removeClass("hidden");
		$('html, body').animate({ scrollTop: $("#calculation-result").offset().top }, 500);
		
		// Display inputs
		var input_table = $("#input-table").find("tbody");
		input_table.html("");
		$.each(response.input, function (name, value) {
			if (name.indexOf("tracking") !== -1) { return; }
			if (name.indexOf("calc") !== -1) { return; }
			name = name.replace("_", " ").replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
			input_table.append('<tr><th>'+name+'</th><td>'+value+'</td></tr>');
		});
		
		// VMR Plot
		var vmrFile = "/atmos/outputs/" + response.vmr_file;
		var vmrPlot = $('#vmrPlot')[0];
		var vmrLayout = {
			xaxis: { type: "log", range:[-11,0.5],      title: "Abundance",      titlefont:{size:12}},
			yaxis: { type: "log", autorange:"reversed", title: "Pressure [bar]", titlefont:{size:12}},
			legend: { xanchor: "left", yanchor:"top", y:1.0, x:0.0},
			margin: { l:50, r:0, b:30, t:0, pad:0 }
		};
		var vmrY = "Press";
		var vmrX = ["H2O", "CH4", "C2H6", "CO2", "O2", "O3", "CO", "H2CO", "HNO3", "NO2", "SO2", "OCS"];
		plotData(vmrPlot, vmrLayout, vmrFile, vmrY, vmrX);

		// TP Plot
		var tpFile = "/atmos/outputs/" + response.tp_file;
		var tpPlot = $('#tpPlot')[0];
		var tpLayout = {
			xaxis: { type: "linear", autorange:true,       title: "Temperature [K]", titlefont:{size:12}},
			yaxis: { type: "log",    autorange:"reversed", title: "Pressure [bar]",  titlefont:{size:12}},
			legend: { xanchor: "right", yanchor: "bottom", y: 0.05, x: 1.0},
			margin: { l:50, r:0, b:30, t:0, pad:0 }
		};
		var tpY = "Press";
		var tpX = ["Temp"];
		plotData(tpPlot, tpLayout, tpFile, tpY, tpX);
		
		return false;
	}
	function plotData (plot, layout, output_file_url, yTitle, xList) {
		$.get(output_file_url, function (file) {
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
				data.push({x: xData, y: yData, name: name, type: "line"});
			}
			
			Plotly.newPlot(plot, data, layout);
		});
	}
	function isCookiesEnabled () {
		if (navigator.cookieEnabled) return true;
		// set and read cookie
		document.cookie = "cookietest=1";
		var ret = document.cookie.indexOf("cookietest=") !== -1;
		// delete cookie
		document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
		return ret;
	}
	function getDateTimeText() {
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
	}
	loadOldCalculations();
});