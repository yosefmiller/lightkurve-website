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
	
	/***** FORM SUBMISSION *****/
    $.FORM_PREFIX = "/atmos/";
    $.validationConfigFields = {
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
    $.plotResult = function (response) {
        // VMR Plot
        var vmrFile = "outputs/" + response.vmr_file;
        var vmrPlot = $('#vmrPlot')[0];
        var vmrLayout = {
            xaxis: { type: "log", range:[-11,0.5],      title: "Abundance",      titlefont:{size:12}},
            yaxis: { type: "log", autorange:"reversed", title: "Pressure [bar]", titlefont:{size:12}},
            legend: { xanchor: "left", yanchor:"top", y:1.0, x:0.0},
            margin: { l:50, r:0, b:30, t:0, pad:0 }
        };
        var vmrY = "Press";
        var vmrX = ["H2O", "CH4", "C2H6", "CO2", "O2", "O3", "CO", "H2CO", "HNO3", "NO2", "SO2", "OCS"];
        $.plotData(vmrPlot, vmrLayout, vmrFile, vmrY, vmrX);

        // TP Plot
        var tpFile = "outputs/" + response.tp_file;
        var tpPlot = $('#tpPlot')[0];
        var tpLayout = {
            xaxis: { type: "linear", autorange:true,       title: "Temperature [K]", titlefont:{size:12}},
            yaxis: { type: "log",    autorange:"reversed", title: "Pressure [bar]",  titlefont:{size:12}},
            legend: { xanchor: "right", yanchor: "bottom", y: 0.05, x: 1.0},
            margin: { l:50, r:0, b:30, t:0, pad:0 }
        };
        var tpY = "Press";
        var tpX = ["Temp"];
        $.plotData(tpPlot, tpLayout, tpFile, tpY, tpX);
    };
	$.initValidation();
	$.initCalculationList();
});