$(document).ready(function(){
    /***** FORM ELEMENTS *****/
    /* Nice block level radio selection */
    $(".radio-chooser-item input, .radio-chooser-item label").click(function () {
        $(".radio-chooser-item").removeClass("radio-chooser-selected");
        $(".radio-chooser input:checked").parent().addClass("radio-chooser-selected");

        var kepler_section              = $(".kepler-section");
        var tess_section                = $(".tess-section");
        var tpf_section                 = $(".tpf-section");
        var lc_section                  = $(".lc-section");
        var target_section              = $("#target-section");

        var photo_aperture_section      = $(".photo-aperture-section");
        var photo_prf_section           = $(".photo-prf-section");
        var aperture_percent_section    = $(".aperture-percent-section");
        var aperture_manual_section     = $(".aperture-manual-section");
        switch ( $(this).parent().find("input").attr("id") ) {
            case "search_light_curve":
                tess_section.hide();
                tpf_section.hide();
                kepler_section.show();
                lc_section.show();
                target_section.attr("class", "col-md-6");
                break;
            case "search_target_pixel":
                tess_section.hide();
                lc_section.hide();
                kepler_section.show();
                tpf_section.show();
                target_section.attr("class", "col-md-6");
                break;
            case "search_tesscut":
                kepler_section.hide();
                lc_section.hide();
                tess_section.show();
                tpf_section.show();
                target_section.attr("class", "col-md-4");
                break;

            case "photometry_type_aperture":
                photo_prf_section.hide();
                photo_aperture_section.show();
                break;
            case "photometry_type_prf":
                photo_aperture_section.hide();
                photo_prf_section.show();
                break;
            case "aperture_type_percent":
                aperture_manual_section.hide();
                aperture_percent_section.show();
                break;
            case "aperture_type_manual":
                aperture_manual_section.show();
                aperture_percent_section.hide();
                break;
        }
    });

    /* Popover Help Tips */
    $('[data-toggle="popover"]').popover({
        trigger: "manual",
        html: true,
        animation: false
    }).on('mouseenter', function () {
        var _this = this;
        $(this).popover('show');
        $('.popover').on('mouseleave', function () {
            $(_this).popover('hide');
        });
    }).on('mouseleave', function () {
        var _this = this;
        setTimeout(function () {
            if (!$('.popover:hover').length) {
                $(_this).popover('hide');
            }
        }, 300);
    });

    /* Mission Selection */
    $("#mission").change(function () {
        var limit_quarter_section  = $("#limit-quarter-section");
        var limit_campaign_section = $("#limit-campaign-section");
        var limit_sector_section   = $("#limit-sector-section");

        var mission_list = $("#mission").val() || [];
        mission_list.indexOf("kepler") > -1 ? limit_quarter_section.show()   : limit_quarter_section.hide();
        mission_list.indexOf("k2") > -1     ? limit_campaign_section.show()  : limit_campaign_section.hide();
        mission_list.indexOf("tess") > -1   ? limit_sector_section.show()    : limit_sector_section.hide();
    });

    /* Quarter/Campaign/Tess Selection */
    var option_list = [];
    for (var i = 1; i <= 30; i++) {
        option_list.push({"id": i, "text": i.toString()});
    }
    $("#campaign, #quarter, #sector").select2({
        theme: "bootstrap",
        width: "100%",
        data: option_list,
        multiple: true
    });

    /* Quality Bitmask */
    $("#quality_bitmask").select2({
        theme: "bootstrap",
        width: "100%",
        tags: true
    });

    /* Cadence Selection */
    $("#cadence").change(function () {
        var cadence_month_section = $("#cadence-month-section");
        switch ($("#cadence").val()) {
            case "long":
                cadence_month_section.hide();
                break;
            case "short":
                cadence_month_section.show();
                break;
        }
    });

    /* Search Button */
    $("#search_button, #run_button").click(function () {
        var is_search_only = $(this).is("#search_button") ? "1" : "";
        $("#is_search_only").val(is_search_only);
    });

    /* Manual Aperture Grid handlers */
    $("#aperture_generate_mask_btn").click(function (e) {
        e.preventDefault();
        var rows = parseInt($("#aperture_rows").val());
        var cols = parseInt($("#aperture_columns").val());
        var box = $("#aperture-grid");
        var html = "";

        for (var i = 0; i < rows*cols; i++) {
            html += '<div style="flex-basis: '+ 100/cols +'%;"></div>';
        }
        box.html(html);

        $("#aperture-grid > div").click(function(e){
            $(this).toggleClass("on");
            var input = $("#aperture_custom");
            var boxes = $("#aperture-grid div");
            var result = [];
            boxes.each(function () {
                result.push( $(this).hasClass("on") ? "1" : "0" );
            });
            result = result.join(",");
            input.val(result);
        });
        return false;
    });

    $("#isCustomAperture").change(function () {
        var aperture_section = $(".aperture-section-custom");
        var aperture_subsections = $(".aperture-manual-section, .aperture-percent-section");
        if ($(this).is(":checked")) { aperture_section.show(); }
        else {
            aperture_section.hide();
            aperture_subsections.hide();
        }
    });

    $("#isRemoveOutliers").change(function () {
        var outlier_section = $("#outlier-section");
        if ($(this).is(":checked")) { outlier_section.show(); }
        else { outlier_section.hide(); }
    });

    $("#isSffCorrection").change(function () {
        var sff_correction_section = $("#sff-correction-section");
        if ($(this).is(":checked")) { sff_correction_section.show(); }
        else { sff_correction_section.hide(); }
    });

    $("#isPeriodogram").change(function () {
        var periodogram_section = $("#periodogram-section");
        if ($(this).is(":checked")) { periodogram_section.show(); }
        else { periodogram_section.hide(); }
    });

    $("#isFlatten").change(function () {
        var flatten_section = $(".flatten-section");
        if ($(this).is(":checked")) { flatten_section.show(); }
        else { flatten_section.hide(); }
    });

    $("#isFold").change(function () {
        var fold_section = $(".fold-section");
        if ($(this).is(":checked")) { fold_section.show(); }
        else { fold_section.hide(); }
    });

    $("#isNormalize").change(function () {
        var normalize_section = $(".normalize-section");
        if ($(this).is(":checked")) { normalize_section.show(); }
        else { normalize_section.hide(); }
    });

    $("#isBin").change(function () {
        var bin_section = $(".bin-section");
        if ($(this).is(":checked")) { bin_section.show(); }
        else { bin_section.hide(); }
    });

    $("#isRiverPlot").change(function () {
        var riverplot_section = $(".riverplot-section");
        if ($(this).is(":checked")) { riverplot_section.show(); }
        else { riverplot_section.hide(); }
    });

    $("#isCDPP").change(function () {
        var cdpp_section = $(".cdpp-section");
        if ($(this).is(":checked")) { cdpp_section.show(); }
        else { cdpp_section.hide(); }
    });

    /***** END FORM ELEMENTS *****/

    /***** FORM SUBMISSION *****/
    $.FORM_PREFIX = "/";
    // $.FORM_INPUT_ORDER = [ "data_archive", "flux_type", "target", "limiting_factor", "quarter_campaign",
    //                         "quality_bitmask", "cadence", "month", "search_radius", "limit_targets" ];
    $.validationConfigFields = {
        calc_name: {
            validators: {
                notEmpty: {
                    message: 'Calculation name is required'
                }
            }
        },
        data_archive: {
            validators: {
                notEmpty: {
                    message: 'Data archive selection is required'
                }
            }
        },
        flux_type: {
            validators: {
                notEmpty: {
                    message: 'Flux type selection is required'
                }
            }
        },
        target: {
            validators: {
                notEmpty: {
                    message: 'Target ID/name is required'
                }
            }
        },
        quality_bitmask: {
            validators: {
                notEmpty: {
                    message: 'Quality bitmask selection is required'
                }
            }
        },
        cadence: {
            validators: {
                notEmpty: {
                    message: 'Cadence selection is required'
                }
            }
        },
        month: {
            validators: {
                notEmpty: {
                    message: 'Month selection is required'
                }
            }
        },
        search_radius: {
            validators: {
                notEmpty: {
                    message: 'Search radius is required'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Search radius must be a greater than zero'
                }
            }
        },
        limit_targets: {
            validators: {
                integer: {
                    message: 'Target limit must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Target limit must be a greater than zero'
                }
            }
        },
        cutout_size: {
            validators: {
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Cutout size must be an number greater than zero'
                }
            }
        },
        outlier_sigma: {
            validators: {
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Sigma must be a float greater than zero'
                }
            }
        },
        flatten_window: {
            validators: {
                integer: {
                    message: 'Window length must be an odd integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Window length must be an odd integer greater than zero'
                }
            }
        },
        flatten_polyorder: {
            validators: {
                integer: {
                    message: 'Polynomial order must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Polynomial order must be an integer greater than zero'
                },
                lessThanOther: {
                    element: "flatten_window",
                    name: "window length"
                }
            }
        },
        flatten_tolerance: {
            validators: {
                integer: {
                    message: 'Break tolerance must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: true,
                    message: 'Break tolerance must be an integer greater than or equal to zero'
                }
            }
        },
        flatten_iterations: {
            validators: {
                integer: {
                    message: 'Number of iterations must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Number of iterations must be an integer greater than zero'
                }
            }
        },
        flatten_sigma: {
            validators: {
                integer: {
                    message: 'Number of sigma must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Number of sigma must be an integer greater than zero'
                }
            }
        },
        fold_period: {
            validators: {
                numeric: {
                    message: 'Period must be a number'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Period must be greater than zero'
                }
            }
        },
        fold_phase: {
            validators: {
                numeric: {
                    message: 'Phase must be a number'
                }
            }
        },
        bin_size: {
            validators: {
                notEmpty: {
                    message: 'Time interval is required'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Time interval must be greater than zero'
                }
            }
        },
        bin_count: {
            validators: {
                integer: {
                    message: 'Number of bins must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Number of bins must be greater than zero'
                }
            }
        },
        river_plot_period: {
            validators: {
                numeric: {
                    message: 'Period must be a number'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Period must be greater than zero'
                },
                notEmpty: {
                    message: 'Period is required'
                }
            }
        },
        river_plot_time: {
            validators: {
                numeric: {
                    message: 'Epoch time must be a number'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Epoch time must be greater than zero'
                }
            }
        },
        river_plot_points: {
            validators: {
                integer: {
                    message: 'Number of points must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Number of points must be greater than zero'
                }
            }
        },
        river_plot_phase_min: {
            validators: {
                numeric: {
                    message: 'Minimum phase must be a number'
                }
            }
        },
        river_plot_phase_max: {
            validators: {
                numeric: {
                    message: 'Maximum phase must be a number'
                }
            }
        },
        river_plot_method: {
            validators: {
                notEmpty: {
                    message: 'River method is required'
                }
            }
        },
        cdpp_duration: {
            validators: {
                integer: {
                    message: 'Transit duration must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Transit duration must be greater than zero'
                }
            }
        },
        cdpp_window: {
            validators: {
                integer: {
                    message: 'Filter width must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Filter width must be greater than zero'
                }
            }
        },
        cdpp_polyorder: {
            validators: {
                integer: {
                    message: 'Polynomial order must be an integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Polynomial order must be greater than zero'
                }
            }
        },
        cdpp_sigma: {
            validators: {
                numeric: {
                    message: 'Sigma must be an number'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Sigma must be greater than zero'
                }
            }
        }
    };
    $.fn.bootstrapValidator.validators.lessThanOther = {
        validate: function(validator, $field, options) {
            var value = $field.val();

            // Get validator settings
            var element = options.element,
                element_name = options.name,
                inclusive = options.inclusive || false;

            // Check if contains number
            if (!$.isNumeric(value)) return true;
            value = parseFloat(value);

            // Get target element value
            var el_value = $("[name='"+element+"']").val();
            if (!$.isNumeric(el_value)) return true;
            el_value = parseFloat(el_value);

            // Check the bounds of the number
            if ((!inclusive && value >= el_value) || (inclusive && value > el_value)) {
                return {
                    valid: false,
                    message: "Must be an integer less than " + (inclusive ? "or equal to " : "") + "value of " + element_name
                };
            }

            return true;
        }
    };
    $.plotResult = function (response) {
        if (!response.search_results) { $("#searchResultsTable").html(""); }
        else { $("#searchResultsTable").html(response.search_results).find("table").addClass("table table-striped"); }

        if (!response.tpf) { Plotly.purge($('#tpfPlot')[0]); }
        else { $.tpfFluxPlot(response, true); }

        if (!response.lc_flux_file) { Plotly.purge($('#lcPlot')[0]); }
        else { $.lcFluxPlot(response, true); }

        if (!response.p_power_file) { Plotly.purge($('#pPlot')[0]); }
        else { $.pPowerPlot(response); }
    };
    $.initValidation();
    $.initCalculationList();

    $.tpfFluxPlot = function (response, isShowAperture) {
        // TargetPixelFile Flux Frame Plot
        var tpfPlot = $('#tpfPlot')[0];
        var tpfFile = response.tpf.flux_file;
        var tpfLayout = {
            title: "Target ID: " + response.tpf.target_id,
            titlefont: {size: 14},
            xaxis: { showgrid: false, title: "Pixel Column Number", titlefont:{size:12}},
            yaxis: { showgrid: false, title: "Pixel Row Number",    titlefont:{size:12}},
            legend: { xanchor: "left", yanchor:"top", y:1.0, x:0.0},
            margin: { l:50, r:0, b:50, t:30, pad:0 },
            barmode: "overlay"
        };
        var tpfCustomData = {
            type: 'heatmap',
            x0: response.tpf.img_extent[0],
            y0: response.tpf.img_extent[2],
            zmin: response.tpf.z_limits[0],
            zmax: response.tpf.z_limits[1]
        };
        var tpfCustomDataList = [
            {
                colorscale: 'Viridis',
                opacity: 1,
                hoverinfo: "x+y+z",
                colorbar: {
                    title: "Flux (e-s-1)",
                    titleside: "right",
                    ticks: 'outside'
                }
            },
            {
                visible: isShowAperture,
                colorscale: [[0, '#FFB5B8'], [1, '#FFB5B8']],
                showscale: false,
                opacity: 0.5,
                hoverinfo: "skip"
            }
        ];
        var tpfX = ["flux", "pipeline_mask"];
        var tpfY = ["flux", "pipeline_mask"];
        $.plotData(tpfPlot, tpfLayout, tpfFile, tpfY, tpfX, tpfCustomData, tpfCustomDataList);
    };

    $.lcFluxPlot = function (response, isLines) {
        // LightCurve Flux Plot
        var lcFile = response.lc_flux_file;
        var lcPlot = $('#lcPlot')[0];
        var lcLayout = {
            xaxis: { title: "Time - 2454833 (days)", titlefont:{size:12}},
            yaxis: { title: "Normalized Flux",  titlefont:{size:12}},
            legend: { xanchor: "right", yanchor: "bottom", y: 0.05, x: 1.0},
            margin: { l:60, r:0, b:50, t:0, pad:0 },
            updatemenus: [{
                buttons: [
                    { label: 'Show lines', args: [{mode: 'line'}],    method: 'restyle' },
                    { label: 'Hide lines', args: [{mode: 'markers'}], method: 'restyle' }
                ],
                direction: 'down',
                pad: {'r': 10, 't': 10},
                showactive: true,
                type: 'dropdown',
                x: 0.02, xanchor: 'left',
                y: 1.10, yanchor: 'top'
            }]
        };
        var lcCustomData = {
            type: 'scatter',
            mode: isLines ? 'line' : 'markers',
            marker: { size: 3 }
        };
        var lcY = "flux";
        var lcX = ["time"];
        $.plotData(lcPlot, lcLayout, lcFile, lcY, lcX, lcCustomData);
    };

    $.pPowerPlot = function (response) {
        // LightCurve Periodogram Plot
        var pFile = response.p_power_file;
        var pPlot = $('#pPlot')[0];
        var pLayout = {
            xaxis: { title: "Frequency [μHz]",     titlefont:{size:12}},
            yaxis: { title: "Power [ppm^2 / μHz]", titlefont:{size:12}},
            legend: { xanchor: "left", yanchor: "bottom", y: 0.05, x: 1.0},
            margin: { l:60, r:0, b:50, t:0, pad:0 },
            updatemenus: [{
                buttons: [
                    { label:'Frequency/linear scale', method:'update', args: [
                        { visible:[true,false] },
                        {
                            xaxis: { type: 'linear', title: "Frequency [μHz]",     titlefont:{size:12}},
                            yaxis: { type: 'linear', title: "Power [ppm^2 / μHz]", titlefont:{size:12}}
                        }
                    ]},
                    { label:'Period/log scale', method:'update', args: [
                        { visible:[false,true] },
                        {
                            xaxis: { type: 'log', title: "Period [d]",     titlefont:{size:12}},
                            yaxis: { type: 'log', title: "Power [ppm^2 / μHz]", titlefont:{size:12}}
                        }
                    ]}
                ],
                direction: 'down',
                pad: {'r': 10, 't': 10},
                showactive: true,
                active: 0,
                type: 'dropdown',
                x: 0.02, xanchor: 'left',
                y: 1.10, yanchor: 'top'
            }]
        };
        var pCustomData = {
            type: 'scatter',
            mode: 'line',
            marker: { size: 3 }
        };
        var pCustomDataList = [
            {visible: true},
            {visible: false}
        ];
        var pY = "power";
        var pX = ["frequencies", "period"];
        $.plotData(pPlot, pLayout, pFile, pY, pX, pCustomData, pCustomDataList);
    };
});