$(document).ready(function(){
    /***** FORM ELEMENTS *****/
    /* Nice block level radio selection */
    $(".radio-chooser-item input, .radio-chooser-item label").click(function () {
        $(".radio-chooser-item").removeClass("radio-chooser-selected");
        $(".radio-chooser input:checked").parent().addClass("radio-chooser-selected");

        var kepler_target_section       = $(".kepler-target-section");
        var kepler_light_curve_section  = $(".kepler-light-curve-section");
        var photo_aperture_section      = $(".photo-aperture-section");
        var photo_prf_section           = $(".photo-prf-section");
        var aperture_percent_section    = $(".aperture-percent-section");
        var aperture_manual_section     = $(".aperture-manual-section");
        switch ( $(this).parent().find("input").attr("id") ) {
            case "kepler_light_curve":
                kepler_target_section.removeClass("hidden");
                kepler_light_curve_section.removeClass("hidden");
                break;
            case "kepler_target_pixel":
                kepler_target_section.removeClass("hidden");
                kepler_light_curve_section.addClass("hidden");
                break;
            // case "tess_target_pixel":
            //     kepler_target_section.addClass("hidden");
            //     kepler_light_curve_section.addClass("hidden");
            //     break;
            case "photometry_type_aperture":
                photo_prf_section.addClass("hidden");
                photo_aperture_section.removeClass("hidden");
                break;
            case "photometry_type_prf":
                photo_aperture_section.addClass("hidden");
                photo_prf_section.removeClass("hidden");
                break;
            case "aperture_type_percent":
                aperture_manual_section.addClass("hidden");
                aperture_percent_section.removeClass("hidden");
                break;
            case "aperture_type_manual":
                aperture_manual_section.removeClass("hidden");
                aperture_percent_section.addClass("hidden");
                break;
        }
    });

    /* Popover Help Tips */
    $('[data-toggle="popover"]').popover();

    /* Quarter/Campaign Selection */
    $("#limiting_factor").change(function () {
        $("#quarter-campaign-section").removeClass("hidden");
    });

    /* Cadence Selection */
    $("#cadence").change(function () {
        var cadence_month_section = $("#cadence-month-section");
        switch ($("#cadence").val()) {
            case "long":
                cadence_month_section.addClass("hidden");
                break;
            case "short":
                cadence_month_section.removeClass("hidden");
                break;
        }
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
        if ($(this).is(":checked")) { aperture_section.removeClass("hidden"); }
        else {
            aperture_section.addClass("hidden");
            aperture_subsections.addClass("hidden");
        }
    });

    $("#isRemoveOutliers").change(function () {
        var outlier_section = $("#outlier-section");
        if ($(this).is(":checked")) { outlier_section.removeClass("hidden"); }
        else { outlier_section.addClass("hidden"); }
    });

    $("#isSffCorrection").change(function () {
        var sff_correction_section = $("#sff-correction-section");
        if ($(this).is(":checked")) { sff_correction_section.removeClass("hidden"); }
        else { sff_correction_section.addClass("hidden"); }
    });

    $("#isPeriodogram").change(function () {
        var periodogram_section = $("#periodogram-section");
        if ($(this).is(":checked")) { periodogram_section.removeClass("hidden"); }
        else { periodogram_section.addClass("hidden"); }
    });

    $("#isFlatten").change(function () {
        var flatten_section = $("#flatten-section");
        if ($(this).is(":checked")) { flatten_section.removeClass("hidden"); }
        else { flatten_section.addClass("hidden"); }
    });

    $("#isFold").change(function () {
        var fold_section = $("#fold-section");
        if ($(this).is(":checked")) { fold_section.removeClass("hidden"); }
        else { fold_section.addClass("hidden"); }
    });

    $("#isBin").change(function () {
        var bin_section = $("#bin-section");
        if ($(this).is(":checked")) { bin_section.removeClass("hidden"); }
        else { bin_section.addClass("hidden"); }
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
        is_quarter_or_campaign: {
            validators: {
                notEmpty: {
                    message: 'Limiting factor selection is required'
                }
            }
        },
        quarter_campaign: {
            validators: {
                choiceTextNumber: {
                    min: 1,
                    max: 30,
                    text: 'all'
                },
                notEmpty: {
                    message: 'Quarter or campaign number is required'
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
        window_length: {
            validators: {
                integer: {
                    message: 'Window length must be an odd integer'
                },
                greaterThan: {
                    value: 0,
                    inclusive: false,
                    message: 'Target limit must be an odd integer greater than zero'
                }
            }
        }
    };
    $.plotResult = function (response) {
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
                },
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
                            yaxis: { type: 'linear', title: "Power [ppm^2 / μHz]", titlefont:{size:12}},
                        }
                    ]},
                    { label:'Period/log scale', method:'update', args: [
                        { visible:[false,true] },
                        {
                            xaxis: { type: 'log', title: "Period [d]",     titlefont:{size:12}},
                            yaxis: { type: 'log', title: "Power [ppm^2 / μHz]", titlefont:{size:12}},
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