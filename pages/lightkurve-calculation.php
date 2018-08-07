<div class="col-md-12">
    <!-- Title -->
    <h2 class="page-header">New LightKurve Calculation</h2>

    <!-- Calculation Form -->
    <form enctype="multipart/form-data" class="form-horizontal" action="" method="post" id="calculation-form">

        <div class="form-group">
            <label class="col-md-3 control-label" for="calc_name">Name</label>

            <div class="col-md-4">
                <input type="text" class="form-control" id="calc_name" name="calc_name" value="My New Calculation">
                <p class="help-block">Provide a name for this calculation</p>
            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <label class="col-md-3 control-label" for="data_archive" style="top: 35px;">Data Archive</label>
            <div class="col-md-9">
                <div class="radio-chooser">
                    <div class="radio-chooser-item">
                        <label class="radio-chooser-content" for="kepler_target_pixel">
                            <input type="radio" name="data_archive" id="kepler_target_pixel" value="kepler_target_pixel" />
                            <div class="radio-chooser-title">Kepler Target Pixel File (MAST)</div>
                        </label>
                    </div>
                    <div class="radio-chooser-item">
                        <label class="radio-chooser-content" for="kepler_light_curve">
                            <input type="radio" name="data_archive" id="kepler_light_curve" value="kepler_light_curve" />
                            <div class="radio-chooser-title">Kepler Light Curve File</div>
                        </label>
                    </div>
                    <!--<div class="radio-chooser-item">
                        <label class="radio-chooser-content" for="tess_target_pixel">
                            <input type="radio" name="targetPixelFile" id="tess_target_pixel" value="tess_target_pixel" />
                            <div class="radio-chooser-title">Tess Target Pixel File</div>
                        </label>
                    </div>-->
                </div>
            </div>
        </div>

        <div class="form-group hidden kepler-light-curve-section">
            <label class="col-md-3 control-label" for="" style="top: 35px;">Flux Type</label>
            <div class="col-md-9">
                <div class="radio-chooser">
                    <div class="radio-chooser-item">
                        <label class="radio-chooser-content" for="sap">
                            <input type="radio" name="flux_type" id="sap" value="sap" />
                            <div class="radio-chooser-title">Simple Aperture Photometry (SAP)</div>
                        </label>
                    </div>
                    <div class="radio-chooser-item">
                        <label class="radio-chooser-content" for="pdcsap">
                            <input type="radio" name="flux_type" id="pdcsap" value="pdcsap" />
                            <div class="radio-chooser-title">Pre-search Data Conditioning SAP (PDCSAP)</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group hidden kepler-target-section">
            <label class="col-md-3 control-label" for="target">Target Pixel File</label>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="target" name="target" placeholder="E.g. 6922244">
                        <p class="help-block">Archive KIC/EPIC ID or object name.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <select id="limiting_factor" name="limiting_factor" class="form-control" data-placeholder="Select Limiting Factor">
                            <option value=""></option>
                            <option value="quarter">Quarter</option>
                            <option value="campaign">Campaign</option>
                        </select>
                        <p class="help-block">Limiting Factor</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 hidden" id="quarter-campaign-section">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="quarter_campaign" name="quarter_campaign" placeholder="Number, list, or 'all'">
                        <p class="help-block">Kepler Quarter or K2 Campaign number</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group hidden kepler-target-section">
            <div class="col-md-3 col-md-offset-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <select id="quality_bitmask" name="quality_bitmask" class="form-control">
                            <option value="default" title="Recommended quality mask" selected>Default</option>
                            <option value="hard" title="Removes more flags, known to remove good data">Hard</option>
                            <option value="hardest" title="Removes all data that has been flagged">Hardest</option>
                        </select>
                        <p class="help-block">Quality Bitmask</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <select id="cadence" name="cadence" class="form-control">
                            <option value="long" selected>Long (default)</option>
                            <option value="short">Short</option>
                        </select>
                        <p class="help-block">Cadence</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 hidden" id="cadence-month-section">
                <div class="form-group">
                    <div class="col-md-12">
                        <select id="month" name="month" class="form-control" data-placeholder="Select Month">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group hidden kepler-target-section">
            <div class="col-md-3 col-md-offset-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search_radius" name="search_radius" value="1">
                            <span class="input-group-addon">arcsec</span>
                        </div>
                        <p class="help-block">Search radius</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="limit_targets" name="limit_targets" placeholder="No limit (default)">
                        <p class="help-block">Limit target results if multiple are present within search radius</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <label class="col-md-3 control-label" for="planet_template">Calculation Options</label>
            <div class="col-md-9">

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox icheck-alizarin">
                            <input type="checkbox" id="isCustomAperture" name="is_custom_aperture" />
                            <label for="isCustomAperture">Custom Aperture</label>
                        </div>
                    </div>

                    <div class="col-md-12 hidden aperture-section">
                        <div class="radio-chooser">
                            <div class="radio-chooser-item">
                                <label class="radio-chooser-content" for="aperture_type_percent">
                                    <input type="radio" name="aperture_type" id="aperture_type_percent" value="aperture_type_percent" />
                                    <div class="radio-chooser-title">Aperture Percentile</div>
                                </label>
                            </div>
                            <div class="radio-chooser-item">
                                <label class="radio-chooser-content" for="aperture_type_manual">
                                    <input type="radio" name="aperture_type" id="aperture_type_manual" value="aperture_type_manual" />
                                    <div class="radio-chooser-title">Manual Aperture</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 hidden aperture-percent-section" style="margin-bottom: 10px;">
                        <input type="number" class="form-control" id="aperture_percent" name="aperture_percent" min="0" max="100" value="95">
                        <p class="help-block">Percentile</p>
                    </div>

                    <div class="col-md-4 hidden aperture-manual-section">
                        <input type="number" class="form-control" id="aperture_rows" name="aperture_rows" min="0" max="30" value="5">
                        <p class="help-block">Pixel Rows</p>
                    </div>

                    <div class="col-md-4 hidden aperture-manual-section">
                        <input type="number" class="form-control" id="aperture_columns" name="aperture_columns" min="0" max="30" value="5">
                        <p class="help-block">Pixel Columns</p>
                    </div>

                    <div class="col-md-4 hidden aperture-manual-section">
                        <div class="btn btn-block btn-fresh" id="aperture_generate_mask_btn">Generate Mask</div>
                    </div>

                    <div class="col-md-12 hidden aperture-manual-section" style="margin-top: 15px;margin-bottom: 15px;">
                        <div id="aperture-grid"></div>
                        <input style="display: none" type="text" id="aperture_custom" name="aperture_custom" value="">
                        <style>
                            .aperture-manual-section {
                                margin-top: 15px;
                            }
                            #aperture-grid {
                                display: flex;
                                flex-wrap: wrap;
                            }
                            #aperture-grid > div {
                                height: 35px;
                                flex-grow: 1;
                                flex-shrink: 1;
                                border: 1px solid #888;
                            }
                            #aperture-grid > div { background-color: #eee; }
                            #aperture-grid > div:hover { background-color: #ddd; }
                            #aperture-grid > div:active { background-color: #ccc; }
                            #aperture-grid > div.on { background-color: #aaa; }
                        </style>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-alizarin">
                            <input type="checkbox" id="isRemoveNans" name="is_remove_nans" />
                            <label for="isRemoveNans">Remove NaNs (removes cadences where the flux is NaN)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-wisteria">
                            <input type="checkbox" id="isRemoveOutliers" name="is_remove_outliers" />
                            <label for="isRemoveOutliers">Remove Outliers (using sigma-clipping)</label>
                        </div>
                    </div>
                    <div class="col-md-6 hidden" id="outlier-section">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="sigma" name="sigma" placeholder="5.0 (default)">
                                <p class="help-block">Sigma (number of standard deviations) to use for clipping outliers</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-greensea">
                            <input type="checkbox" id="isSffCorrection" name="is_sff_correction" />
                            <label for="isSffCorrection">Self Flat Fielding (SFF) correction</label>
                        </div>
                    </div>
                    <div class="col-md-6 hidden" id="sff-correction-section">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="windows" name="windows" placeholder="1 (default)">
                                <p class="help-block">Windows</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-emerland">
                            <input type="checkbox" id="isFillGaps" name="is_fill_gaps" />
                            <label for="isFillGaps">Fill gaps (using linear interpolation)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-greensea">
                            <input type="checkbox" id="isPeriodogram" name="is_periodogram" />
                            <label for="isPeriodogram">Periodogram</label>
                        </div>
                    </div>
                    <div class="col-md-6 hidden" id="periodogram-section">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="frequencies" name="frequencies" placeholder="1 (default)">
                                    <span class="input-group-addon">μHz</span>
                                </div>
                                <p class="help-block">Frequencies</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-pomegranate">
                            <input type="checkbox" id="isFlatten" name="is_flatten" />
                            <label for="isFlatten">Flatten (removes low frequency trend using scipy’s Savitzky-Golay filter)</label>
                        </div>
                    </div>
                    <div class="col-md-6 hidden" id="flatten-section">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="window_length" name="window_length" placeholder="101 (default)">
                                <p class="help-block">Length of the filter window (i.e. the number of coefficients as a positive odd integer)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox icheck-asbestos">
                            <input type="checkbox" id="isFold" name="is_fold" />
                            <label for="isFold">Fold the lightcurve (at a specified period and phase)</label>
                        </div>
                    </div>
                    <div class="col-md-12 hidden" id="fold-section" style="margin-top:15px;">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="period" name="period" placeholder="Leave blank to choose best fit">
                                <p class="help-block">Period upon which to fold</p>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="phase" name="phase" placeholder="0.0 (default)">
                                <p class="help-block">Phase (data points which occur exactly at phase or an integer multiple of phase + n*period have time value 0.0)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox icheck-belizehole">
                            <input type="checkbox" id="isBin" name="is_bin" />
                            <label for="isBin">Bin</label>
                        </div>
                    </div>
                    <div class="col-md-12 hidden" id="bin-section" style="margin-top:15px;">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="bin_size" name="bin_size" placeholder="13 (default)">
                                <p class="help-block">Number of cadences to include in every bin</p>
                            </div>
                            <div class="col-md-6">
                                <select id="bin_method" name="bin_method" class="form-control">
                                    <option value="mean" selected>Mean (default)</option>
                                    <option value="median">Median</option>
                                </select>
                                <p class="help-block">The summary statistic to return for each bin</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <div class="col-md-offset-3 col-md-9">
                <input type="hidden" name="calc_date" id="calc_date" value="" />
                <input type="hidden" name="tracking_id" id="tracking_id" value="0" />
                <button type="submit" class="btn btn-fresh">Submit</button>
            </div>
        </div>

    </form>

    <!-- Result list -->
    <div id="calculation-list"></div>
    <div id="calculation-logs"></div>
    <div id="calculation-result">
        <div class="col-md-6">
            <h4>Plots</h4>
            <div id="lcPlot"  style="width: 100%;"></div>
            <div id="pPlot"   style="width: 100%; margin-top: 10px;"></div>
            <div id="tpfPlot" style="width: 100%; margin-top: 10px;"></div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <h4>Output Values</h4>
            <table class="table table-striped">
                <thead>
                <tr style="text-align: right;">
                    <th>Name</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                <tr><th>Mission</th><td id="mission"></td></tr>
                <tr><th>CDPP noise metric</th><td id="cdpp"></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>