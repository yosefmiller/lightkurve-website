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
            <label class="col-md-3 control-label" for="data_archive" style="margin-top: 30px;">Data Archive</label>
            <div class="col-md-9">
                <div class="radio-chooser">
                    <div class="radio-chooser-item">
                        <input type="radio" name="data_archive" id="kepler_target_pixel" value="kepler_target_pixel" />
                        <label class="radio-chooser-title" for="kepler_target_pixel">Target Pixel File (MAST)</label>
                        <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
                                data-title="Target Pixel File"
                                data-content="Target Pixel Files (TPFs) are a file common to Kepler/K2 and the TESS mission,
                                        which contain movies of the pixel data centered on a single target star.<br/><br/>
                                        TPFs can be thought of as stacks of images, with one image for every timestamp (<i>cadence</i>) the
                                        telescope took data. These images are cut out ‘postage stamps’ of the full observation to make them easier to work with.">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="radio-chooser-item">
	                    <input type="radio" name="data_archive" id="kepler_light_curve" value="kepler_light_curve" />
	                    <label class="radio-chooser-title" for="kepler_light_curve">Light Curve File</label>
                        <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
                                data-title="Light Curve File"
                                data-content="Rather than manually generating light-curves using a Target Pixel File, these files have been
                                pre-generated using NASA’s <a target='_blank' href='https://github.com/nasa/kepler-pipeline/'>
                                Kepler Data Processing Pipeline</a>.<br/><br/>Usually, you will access these files through the
                                <a target='_blank' href='https://archive.stsci.edu/kepler/data_search/search.php'>MAST archive</a>.
                                Kepler light curves from MAST have <a target='_blank' href='https://arxiv.org/pdf/1207.3093.pdf'>some level of processing</a>.">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                        </button>
                    </div>
                    <!--<div class="radio-chooser-item">
                        <input type="radio" name="data_archive" id="tess_target_pixel" value="tess_target_pixel" />
                        <label class="radio-chooser-title" for="tess_target_pixel">Tess Target Pixel File</label>
                    </div>-->
                </div>
            </div>
        </div>

        <div class="form-group hidden kepler-target-section">
            <label class="col-md-3 control-label" for="target">Target Pixel File</label>
	        <div class="col-md-9">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="target" name="target" placeholder="E.g. 6922244">
                                <p class="help-block">Archive KIC/EPIC ID or object name.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4 hidden" id="quarter-campaign-section">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="quarter_campaign" name="quarter_campaign" placeholder="Number, list, or 'all'">
                                <p class="help-block">Kepler Quarter or K2 Campaign number</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select id="quality_bitmask" name="quality_bitmask" class="form-control">
                                    <option value="none" title="No cadences will be ignored (0)">None</option>
                                    <option value="default" title="Recommended quality mask (1130799)" selected>Default</option>
                                    <option value="hard" title="Removes more flags, known to remove good data (1664431)">Hard</option>
                                    <option value="hardest" title="Removes all data that has been flagged (2096639)">Hardest</option>
                                </select>
                                <p class="help-block">Quality Bitmask</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select id="cadence" name="cadence" class="form-control">
                                    <option value="long" selected>Long (default)</option>
                                    <option value="short">Short</option>
                                </select>
                                <p class="help-block">Cadence
                                    <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-html="true"
                                            data-title="Cadence"
                                            data-content="<b>Kepler</b> <i>long cadence (30-min)</i> images and light curves are stored in files
                                    that span a quarter. <i>Short cadence (1-min)</i> images and light curves are stored in files
                                    that span a month.<br/><br/><b>K2</b> <i>long cadence (30-min)</i> images are available for each Campaign.
                                    <i>Short cadence (1-min)</i> images are also available for each Campaign,
                                    and short cadence light curves are produced for campaigns processed or re-processed
                                    since the start of the K2 global uniform reprocessing effort.">
                                        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 hidden" id="cadence-month-section">
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
                <div class="row" style="margin-bottom: 10px">
	                <div class="col-md-4">
		                <div class="input-group">
			                <input type="text" class="form-control" id="search_radius" name="search_radius" value="0.0001">
			                <span class="input-group-addon">arcsec</span>
		                </div>
		                <p class="help-block">Search radius</p>
	                </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="limit_targets" name="limit_targets" placeholder="No limit (default)">
                        <p class="help-block">Limit target results if multiple are present within search radius</p>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="form-group hidden kepler-light-curve-section">
            <label class="col-md-3 control-label" for="" style="margin-top: 15px;">Flux Type</label>
            <div class="col-md-9">
                <div class="radio-chooser" style="margin-top: 0px;">
                    <div class="radio-chooser-item">
                        <input type="radio" name="flux_type" id="sap" value="sap" />
                        <label class="radio-chooser-title" for="sap">Simple Aperture Photometry <small><i>(SAP)</i></small></label>
                    </div>
                    <div class="radio-chooser-item">
                        <input type="radio" name="flux_type" id="pdcsap" value="pdcsap" />
                        <label class="radio-chooser-title" for="pdcsap">Pre-search Data Conditioning SAP</label>
                        <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
                                data-title="PDCSAP Flux"
                                data-content="In <b>Pre-search Data Conditioning SAP (PDCSAP) flux</b>, long term trends have been removed
                                from this data using so-called <i>Cotrending Basis Vectors (CBVs)</i>.<br/><br/>
                                Usually cleaner data than the <i>SAP flux</i> and will have fewer systematic trends.">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="col-md-11 col-md-offset-1">
    
        <div class="form-group">
            <label class="col-md-3 control-label" for="" style="margin-top: 30px">Photometry options</label>
            <div class="col-md-9">
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="radio-chooser">
                            <div class="radio-chooser-item">
                                <input type="radio" name="photometry_type" id="photometry_type_aperture" value="photometry_type_aperture" />
                                <label class="radio-chooser-title" for="photometry_type_aperture">Aperture Photometry</label>
                            </div>
                            <div class="radio-chooser-item">
                                <input type="radio" name="photometry_type" id="photometry_type_prf" value="photometry_type_prf" />
                                <label class="radio-chooser-title" for="photometry_type_prf">PRF Photometry <small><i>(beta)</i></small></label>
                                <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
                                        data-title="PRF Photometry"
                                        data-content="<b>Point Response Function (PRF) Photometry</b> fits a parameterized model to the data.
                                        It offers the ability to separate the signals of overlapping stars in very crowded regions
                                        or star clusters.<br/><br/>Significantly more complicated than <i>aperture photometry</i>
                                        and is prone to a different set of systematics.">
                                    <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-12 hidden photo-aperture-section">
                        <div class="checkbox icheck-alizarin">
                            <input type="checkbox" id="isCustomAperture" name="is_custom_aperture" />
                            <label for="isCustomAperture">Custom Aperture</label>
                            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="right" data-html="true"
                                    data-title="Custom Aperture Mask"
                                    data-content="The point spread function (PSF) of the telescope causes the light from
                                     the star fall onto several different pixels. An <b>aperture</b> determines which pixels
                                     to sum to create a 1-D light curve of the target.<br/><br/>The <i>Kepler</i> pipeline
                                     automatically adds an aperture, though there are some science cases where you might want to
                                     create a <i>different aperture</i>. For example, there may be a nearby contaminant or you
                                     may want to measure the background.">
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                
                    <div class="col-md-12 hidden aperture-section-custom">
                        <div class="radio-chooser">
                            <div class="radio-chooser-item">
                                <input type="radio" name="aperture_type" id="aperture_type_percent" value="aperture_type_percent" />
                                <label class="radio-chooser-title" for="aperture_type_percent">Aperture Percentile</label>
                            </div>
                            <div class="radio-chooser-item">
                                <input type="radio" name="aperture_type" id="aperture_type_manual" value="aperture_type_manual" />
                                <label class="radio-chooser-title" for="aperture_type_manual">Manual Aperture</label>
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
        
            </div>
        </div>
        
        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <label class="col-md-3 control-label" for="">Lightcurve Options</label>
            <div class="col-md-9">

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-alizarin">
                            <input type="checkbox" id="isRemoveNans" name="is_remove_nans" />
                            <label for="isRemoveNans">Remove NaNs <small>(removes cadences where the flux is NaN)</small></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-wisteria">
                            <input type="checkbox" id="isRemoveOutliers" name="is_remove_outliers" />
                            <label for="isRemoveOutliers">Remove Outliers <small>(using sigma-clipping)</small></label>
                        </div>
                    </div>
                    <div class="col-md-6 hidden" id="outlier-section">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="sigma" name="sigma" placeholder="5.0 (default)">
                                <p class="help-block">Sigma <small>(number of standard deviations)</small> to use for clipping outliers</p>
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
                            <label for="isFillGaps">Fill gaps <small>(using linear interpolation)</small></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-pomegranate">
                            <input type="checkbox" id="isFlatten" name="is_flatten" />
                            <label for="isFlatten">Flatten <small>(removes low frequency trend using scipy’s
                            <a target="_blank" href="https://en.wikipedia.org/wiki/Savitzky%E2%80%93Golay_filter">Savitzky-Golay filter</a>)</small></label>
                        </div>
                    </div>
                    <div class="col-md-6 hidden" id="flatten-section">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="window_length" name="window_length" placeholder="101 (default)">
                                <p class="help-block">Length of the filter window <small>(i.e. the number of coefficients as a positive odd integer)</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox icheck-asbestos">
                            <input type="checkbox" id="isFold" name="is_fold" />
                            <label for="isFold">Fold the lightcurve <small>(at a specified period and phase)</small></label>
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
                                <p class="help-block">Phase <small>(data points which occur exactly at phase or an integer multiple of phase + n*period have time value 0.0)</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox icheck-belizehole">
                            <input type="checkbox" id="isBin" name="is_bin" />
                            <label for="isBin">Bin <small>(Reduce the time resolution of the array, taking the average value in each bin)</small></label>
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
	
	            <div class="row">
		            <div class="col-md-6">
			            <div class="checkbox icheck-amethyst">
				            <input type="checkbox" id="isNormalize" name="is_normalize" />
				            <label for="isNormalize">Normalize</label>
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

            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">
    
        <div class="form-group">
            <label class="col-md-3 control-label" for="">Viewing Options</label>
            <div class="col-md-9">
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox icheck-greensea">
                            <input type="checkbox" id="isViewMetadata" name="is_view_metadata" />
                            <label for="isViewMetadata">Inspect raw target metadata from FITS file</label>
                            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="right" data-html="true"
                                    data-title="Custom Aperture"
                                    data-content="Target Pixel Files (TPFs) are a file common to Kepler/K2 and the TESS
                                    mission which contain movies of the pixel data centered on a single target star.<br/><br/>
                                    TPFs are given in FITS files, which you can read more about
                                    <a target='__blank' href='https://fits.gsfc.nasa.gov/fits_primer.html'>here</a>.">
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </button>
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
                <button type="submit" class="btn btn-fresh">Run Calculation</button>
            </div>
        </div>

    </form>

    <!-- Result list -->
    <div id="calculation-list"></div>
    <div id="calculation-logs"></div>
    <div id="calculation-result">
        <div class="col-md-12">
            <h4>Plots</h4>
            <div id="lcPlot"  title="Lightcurve plot"  style="width: 100%;"></div>
            <div id="pPlot"   title="Periodogram plot" style="width: 100%; margin-top: 10px;"></div>
            <div id="tpfPlot" title="TPF plot"         style="width: 100%; margin-top: 10px;"></div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <h4>Output Values</h4>
            <table id="output-table" class="table table-striped">
                <thead>
                <tr style="text-align: right;">
                    <th>Name</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>