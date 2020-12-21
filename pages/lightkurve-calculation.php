<div class="col-md-12">
    <!-- Title -->
    <h3 class="page-header">New LightKurve Calculation</h3>

    <!-- Calculation Form -->
    <form enctype="multipart/form-data" class="form-horizontal" action="" method="post" id="calculation-form">
		<!-- CALCULATION NAME -->
        <div class="row">
            <label class="col-md-2 control-label" for="calc_name">Name</label>

            <div class="col-md-4 form-group">
                <input type="text" class="form-control" id="calc_name" name="calc_name" placeholder="My new calculation (optional)">
                <label for="calc_name" class="help-block">Provide a name for this calculation</label>
            </div>
        </div>
	    
		<!-- DATA ARCHIVE -->
        <div class="row">
            <label class="col-md-2 control-label" for="data_archive" style="margin-top: 30px;">Data Archive</label>
            <div class="col-md-10 form-group">
                <div class="radio-chooser">
                    <div class="radio-chooser-item">
                        <input type="radio" name="data_archive" id="search_target_pixel" value="search_target_pixel" />
                        <label class="radio-chooser-title" for="search_target_pixel">Target Pixel File</label>
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
		                <input type="radio" name="data_archive" id="search_tesscut" value="search_tesscut" />
		                <label class="radio-chooser-title" for="search_tesscut">TESS Cutouts</label>
		                <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
		                        data-title="TESS Cutouts"
		                        data-content="Searches MAST for TESS Full Frame Image cutouts containing a desired target or region.<br/><br/>
	                            This feature uses the <a target='_blank' href='https://mast.stsci.edu/tesscut/'>TESScut service</a>
	                            provided by the TESS data archive at MAST. If you use this service in your work, please cite
	                            <a target='_blank' href='https://ascl.net/code/v/2239'>TESScut</a> in your publications.">
			                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
		                </button>
	                </div>
	                <div class="radio-chooser-item">
	                    <input type="radio" name="data_archive" id="search_light_curve" value="search_light_curve" />
	                    <label class="radio-chooser-title" for="search_light_curve">Light Curve File</label>
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
                </div>
            </div>
        </div>
        <div class="row collapse tess-section kepler-section">
            <label class="col-md-2 control-label" for="target">Search Parameters</label>
	        <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6 form-group kepler-section tess-section" id="target-section">
                        <input type="text" class="form-control" id="target" name="target" placeholder="E.g. 6922244">
                        <label for="target" class="help-block">Target object name or KIC/EPIC ID.
                            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="bottom" data-html="true"
                                    data-title="Target"
                                    data-content='Target around which to search. Valid inputs include:
                                <ul style="max-width:100%;width:400px;padding-left:15px;">
								<li>The name of the object as a string, e.g. "Kepler-10".</li>
								<li>The KIC or EPIC identifier as an integer, e.g. 11904151.</li>
								<li>A coordinate string in decimal format, e.g. "285.67942179 +50.24130576".</li>
								<li>A coordinate string in sexagesimal format, e.g. "19:02:43.1 +50:14:28.7".</li></ul>'>
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </button>
                        </label>
                    </div>
                    <div class="col-md-6 form-group kepler-section">
                        <select id="mission" name="mission[]" class="form-control" data-placeholder="Select Mission" multiple>
                            <option value="k2" selected>K2</option>
                            <option value="kepler" selected>Kepler</option>
                            <option value="tess" selected>TESS</option>
                        </select>
                        <label for="mission" class="help-block">Mission</label>
                    </div>
	                <div class="clearfix kepler-section visible hidden-sm"></div>
                    <div class="col-md-4 form-group kepler-section"              id="limit-quarter-section">
                        <select class="form-control" id="quarter" name="quarter[]" data-placeholder="All (default)" multiple></select>
                        <label for="quarter" class="help-block">Kepler Quarter number(s)</label>
                    </div>
                    <div class="col-md-4 form-group kepler-section"              id="limit-campaign-section">
                        <select class="form-control" id="campaign" name="campaign[]" data-placeholder="All (default)" multiple></select>
                        <label for="campaign" class="help-block">K2 Campaign number(s)</label>
                    </div>
                    <div class="col-md-4 form-group kepler-section tess-section" id="limit-sector-section">
                        <select class="form-control" id="sector" name="sector[]" data-placeholder="All (default)" multiple></select>
                        <label for="sector" class="help-block">TESS Sector number(s)</label>
                    </div>
	                <div class="clearfix kepler-section visible hidden-sm"></div>
	                <div class="col-md-4 form-group kepler-section">
		                <div class="input-group">
			                <input type="text" class="form-control" id="search_radius" name="search_radius" value="0.0001">
			                <span class="input-group-addon">arcsec</span>
		                </div>
		                <label for="search_radius" class="help-block">Search radius</label>
	                </div>
                    <div class="col-md-4 form-group kepler-section">
                        <select id="cadence" name="cadence" class="form-control">
                            <option value="long" selected>Long (default)</option>
                            <option value="short">Short</option>
                        </select>
                        <label class="help-block" for="cadence">Cadence
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
                        </label>
                    </div>
                    <div class="col-md-4 form-group kepler-section collapse" id="cadence-month-section">
                        <select id="month" name="month[]" class="form-control" data-placeholder="Select month(s)" multiple>
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                        <label for="month" class="help-block">Month</label>
                    </div>
	                <div class="clearfix kepler-section visible hidden-sm"></div>
	                <div class="col-md-4 form-group kepler-section">
		                <input type="text" class="form-control" id="limit_targets" name="limit_targets" placeholder="No limit (default)">
		                <label for="limit_targets" class="help-block">Maximum number of target results to return</label>
	                </div>
	                <div class="col-md-4 kepler-section tess-section">
		                <input type="hidden" name="is_search_only" id="is_search_only" value="" />
		                <button type="submit" class="btn btn-fresh btn-block" id="search_button">Search</button>
	                </div>
                </div>
            </div>
        </div>
        <div class="row collapse tess-section kepler-section">
            <label class="col-md-2 control-label" for="quality_bitmask">Download Options</label>
	        <div class="col-md-10">
                <div class="row">
	                <div class="col-md-4 form-group kepler-section">
		                <select id="quality_bitmask" name="quality_bitmask" class="form-control">
			                <option value="none" title="No cadences will be ignored (0)">None (0)</option>
			                <option value="default" title="Recommended quality mask (1130799)" selected>Default (1130799)</option>
			                <option value="hard" title="Removes more flags, known to remove good data (1664431)">Hard (1664431)</option>
			                <option value="hardest" title="Removes all data that has been flagged (2096639)">Hardest (2096639)</option>
		                </select>
		                <label for="quality_bitmask" class="help-block">Quality Bitmask (to remove bad cadences)</label>
	                </div>
	                <div class="col-md-4 form-group tess-section">
		                <div class="input-group">
		                    <input type="text" class="form-control" id="cutout_size" name="cutout_size" placeholder="5 (default)">
			                <span class="input-group-addon">pixels</span>
		                </div>
		                <label for="cutout_size" class="help-block">Cutout Size (side length)</label>
	                </div>
                </div>
            </div>
        </div>
	    <hr class="col-md-11 col-md-offset-1 collapse tess-section kepler-section">
	    
	    <!-- PHOTOMETRY OPTIONS -->
        <div class="row collapse tess-section kepler-section">
            <label class="col-md-2 control-label" for="" style="margin-top: 30px">Photometry options</label>
            <div class="col-md-10">
            
                <div class="row">
	                <!-- Photometry type -->
                    <div class="col-md-12 form-group collapse tpf-section" style="margin:0">
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
	                
	                <!-- Flux type -->
	                <div class="col-md-12 form-group collapse lcf-section">
		                <div class="radio-chooser">
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
	            
	            <!-- Custom aperture -->
	            <div class="row">
	                <!-- Custom aperture checkbox -->
                    <div class="col-md-12 collapse photo-aperture-section">
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
                    
		            <!-- Aperture type -->
                    <div class="col-md-12 form-group collapse aperture-section-custom">
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
                    
		            <!-- Aperture type: percentile -->
                    <div class="col-md-6 form-group collapse aperture-percent-section" style="margin-bottom: 10px;">
                        <input type="number" class="form-control" id="aperture_percent" name="aperture_percent" min="0" max="100" value="95">
                        <label for="aperture_percent" class="help-block">Percentile</label>
                    </div>
                    
		            <!-- Aperture type: manual -->
                    <div class="col-md-4 form-group collapse aperture-manual-section">
                        <input type="number" class="form-control" id="aperture_rows" name="aperture_rows" min="0" max="30" value="5">
                        <label for="aperture_rows" class="help-block">Pixel Rows</label>
                    </div>
                    <div class="col-md-4 form-group collapse aperture-manual-section">
                        <input type="number" class="form-control" id="aperture_columns" name="aperture_columns" min="0" max="30" value="5">
                        <label for="aperture_columns" class="help-block">Pixel Columns</label>
                    </div>
                    <div class="col-md-4 collapse aperture-manual-section">
                        <div class="btn btn-block btn-fresh" id="aperture_generate_mask_btn">Generate Mask</div>
                    </div>
                    <div class="col-md-12 collapse aperture-manual-section" style="margin-top: 15px;margin-bottom: 15px;">
                        <div id="aperture-grid"></div>
                        <input type="hidden" id="aperture_custom" name="aperture_custom" value="">
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
        <hr class="col-md-11 col-md-offset-1 collapse tess-section kepler-section">
		
		<!-- LIGHTCURVE OPTIONS -->
        <div class="row collapse lightcurve-section">
            <label class="col-md-2 control-label" for="">Lightcurve Options</label>
            <div class="col-md-10">
	            <!-- Remove NaNs -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-alizarin">
                            <input type="checkbox" id="isRemoveNans" name="is_remove_nans" />
                            <label for="isRemoveNans">Remove NaNs <small>(removes cadences where the flux is NaN)</small></label>
                        </div>
                    </div>
                </div>

	            <!-- Remove outliers -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-wisteria">
                            <input type="checkbox" id="isRemoveOutliers" name="is_remove_outliers" />
                            <label for="isRemoveOutliers">Remove Outliers <small>(using sigma-clipping)</small></label>
                        </div>
                    </div>
                    <div class="col-md-6 form-group collapse" id="outlier-section">
                        <div class="input-group">
                            <input type="text" class="form-control" id="outlier_sigma" name="outlier_sigma" placeholder="5.0 (default)">
                            <span class="input-group-addon">sigma</span>
                        </div>
                        <label for="outlier_sigma" class="help-block">Number of standard deviations <small>to use for clipping outliers</small></label>
                    </div>
                </div>

	            <!-- SFF correction -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-greensea">
                            <input type="checkbox" id="isSffCorrection" name="is_sff_correction" />
                            <label for="isSffCorrection">Self Flat Fielding (SFF) correction</label>
                        </div>
                    </div>
                    <div class="col-md-6 form-group collapse" id="sff-correction-section">
                        <input type="text" class="form-control" id="sff_windows" name="sff_windows" placeholder="1 (default)">
                        <label for="sff_windows" class="help-block">Windows</label>
                    </div>
                </div>

	            <!-- Fill gaps -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-emerland">
                            <input type="checkbox" id="isFillGaps" name="is_fill_gaps" />
                            <label for="isFillGaps">Fill gaps in time <small>(using linear interpolation of random white Gaussian noise)</small></label>
                        </div>
                    </div>
                </div>

	            <!-- Flatten -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-pomegranate">
                            <input type="checkbox" id="isFlatten" name="is_flatten" />
                            <label for="isFlatten">Flatten <small>(removes low frequency trend using scipy’s
                            <a target="_blank" href="https://en.wikipedia.org/wiki/Savitzky%E2%80%93Golay_filter">Savitzky-Golay filter</a>)</small></label>
                        </div>
                    </div>
	                <div class="col-md-6 form-group collapse flatten-section">
		                <input type="text" class="form-control" id="flatten_window" name="flatten_window" placeholder="101 (default)">
		                <label for="flatten_window" class="help-block">Length of the filter window <small>(i.e. the number of coefficients as a positive odd integer)</small></label>
	                </div>
	                
	                <div class="clearfix collapse flatten-section visible hidden-sm"></div>
                 
	                <div class="col-md-6 form-group collapse flatten-section">
		                <input type="text" class="form-control" id="flatten_polyorder" name="flatten_polyorder" placeholder="2 (default)">
                        <label for="flatten_polyorder" class="help-block">Order of the polynomial used to fit the samples <small>(must be less than <i>window length</i>)</small></label>
                    </div>
	                <div class="col-md-6 form-group collapse flatten-section">
                        <input type="text" class="form-control" id="flatten_tolerance" name="flatten_tolerance" placeholder="5 (default)">
                        <label for="flatten_tolerance" class="help-block">Break tolerance <small>(splits flux into several lightcurves to apply filter)</small>
                            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
                                    data-title="Break Tolerance"
                                    data-content="<div style='max-width:100%;width:350px'>If there are large gaps in time, <i>flatten</i> will split the flux into several sub-lightcurves
                                    and apply <i>Savitzky-Golay filter</i> to each individually. A gap is defined as a period in time larger than
                                    <i>break tolerance</i> times the median gap.<br/><br/><b>To disable this feature, set <i>break tolerance</i> to 0.</b></div>">
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </button>
                        </label>
                    </div>
	
	                <div class="clearfix collapse flatten-section visible hidden-sm"></div>
	                
	                <div class="col-md-6 form-group collapse flatten-section">
                        <input type="text" class="form-control" id="flatten_iterations" name="flatten_iterations" placeholder="3 (default)">
                        <label for="flatten_iterations" class="help-block">Number of iterations <small>(to iteratively sigma clip and flatten)</small></label>
                    </div>
	                <div class="col-md-6 form-group collapse flatten-section">
                        <input type="text" class="form-control" id="flatten_sigma" name="flatten_sigma" placeholder="3 (default)">
                        <label for="flatten_sigma" class="help-block">Number of sigma <small>(above which to remove outliers from the flatten)</small></label>
                    </div>
                </div>

	            <!-- Stitch -->
                <div class="row collapse lcf-section">
                    <div class="col-md-6">
                        <div class="checkbox icheck-peterriver">
                            <input type="checkbox" id="isStitch" name="is_stitch" />
                            <label for="isStitch">Stitch search results together <small>(makes one long lightcurve)</small></label>
                        </div>
                    </div>
                </div>

	            <!-- Fold -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox icheck-asbestos">
                            <input type="checkbox" id="isFold" name="is_fold" />
                            <label for="isFold">Fold the lightcurve <small>(at a specified period and phase)</small></label>
                        </div>
                    </div>
                    <div class="col-md-6 form-group collapse fold-section" style="margin-top:15px;">
                        <div class="input-group">
                            <input type="text" class="form-control" id="fold_period" name="fold_period" placeholder="Leave blank to choose best fit">
                            <span class="input-group-addon">days</span>
                        </div>
                        <label for="fold_period" class="help-block">Period upon which to fold</label>
                    </div>
                    <div class="col-md-6 form-group collapse fold-section" style="margin-top:15px;">
                        <input type="text" class="form-control" id="fold_phase" name="fold_phase" placeholder="0.0 (default)">
                        <label for="fold_phase" class="help-block">Phase <small>(data points which occur exactly at phase or an integer multiple of phase + n*period have time value 0.0)</small></label>
                    </div>
                </div>

	            <!-- Bin -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-belizehole">
                            <input type="checkbox" id="isBin" name="is_bin" />
                            <label for="isBin">Bin <small>(reduce the time resolution of the array, taking the average value in each bin)</small></label>
                        </div>
                    </div>
	                <div class="clearfix collapse bin-section visible hidden-sm"></div>
                 
	                <div class="col-md-4 form-group collapse bin-section" style="margin-top:15px;">
                        <div class="input-group">
                            <input type="text" class="form-control" id="bin_size" name="bin_size" placeholder="13 (default)">
                            <span class="input-group-addon">cadences</span>
                        </div>
                        <label for="bin_size" class="help-block">Number of cadences to include in every bin</label>
	                </div>
	                
	                <div class="col-md-4 form-group collapse bin-section" style="margin-top:15px;">
                        <div class="input-group">
                            <input type="text" class="form-control" id="bin_count" name="bin_count" placeholder="Overrides bin size">
                            <span class="input-group-addon">bins</span>
                        </div>
                        <label for="bin_count" class="help-block">Number of bins</label>
                    </div>

                    <div class="col-md-4 form-group collapse bin-section" style="margin-top:15px;">
			            <select class="form-control" id="bin_method" name="bin_method">
				            <option value="mean" selected>Mean (default)</option>
				            <option value="median">Median</option>
			            </select>
			            <label for="normalize_unit" class="help-block">Summary statistic method</label>
		            </div>
                </div>
	
	            <!-- Normalize -->
	            <div class="row">
		            <div class="col-md-6">
			            <div class="checkbox icheck-amethyst">
				            <input type="checkbox" id="isNormalize" name="is_normalize" />
				            <label for="isNormalize">Normalize</label>
			            </div>
		            </div>
		            <div class="col-md-6 form-group collapse normalize-section">
			            <select class="form-control" id="normalize_unit" name="normalize_unit">
				            <option value="unscaled" selected>Unscaled (default)</option>
				            <option value="percent">Percent</option>
				            <option value="ppt">Parts per Thousand (ppt)</option>
				            <option value="ppm">Parts per Million (ppm)</option>
			            </select>
			            <label for="normalize_unit" class="help-block">Relative unit</label>
		            </div>
	            </div>

            </div>
        </div>
        <hr class="col-md-11 col-md-offset-1 collapse lightcurve-section">
        
		<!-- CONVERT LIGHTCURVE -->
        <div class="row collapse lightcurve-section">
            <label class="col-md-2 control-label" for="">Convert Lightcurve</label>
            <div class="col-md-10">
	            <!-- Periodogram -->
	            <div class="row">
		            <div class="col-md-12">
			            <div class="checkbox icheck-greensea">
				            <input type="checkbox" id="isPeriodogram" name="is_periodogram" />
				            <label for="isPeriodogram">Periodogram</label>
			            </div>
		            </div>
		            <div class="clearfix collapse p-section visible hidden-sm" style="margin-bottom: 15px"></div>
		            
		            <!-- Method -->
		            <div class="col-md-6 form-group collapse p-section">
			            <select class="form-control" id="p_method" name="p_method" data-placeholder="Select extraction method">
				            <option value=""></option>
				            <option value="lombscargle">Lomb Scargle (default)</option>
				            <option value="boxleastsquares">Box Least Squares (BLS)</option>
			            </select>
			            <label for="p_method" class="help-block">Method</label>
		            </div>
		            
		            <!-- Lomb Scargle -->
		            <div class="col-md-12 collapse p-ls-section">
			            <div class="row">
				            <!-- Normalization -->
				            <div class="col-md-4 form-group">
					            <select class="form-control" id="p_ls_normalization" name="p_ls_normalization">
						            <option value="amplitude" selected>Amplitude (default)</option>
						            <option value="psd">Power Spectral Density (PSD)</option>
					            </select>
					            <label for="p_ls_normalization" class="help-block">Normalization of the spectrum
						            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
						                    data-title="Normalization"
						                    data-content='Users doing asteroseismology on classical pulsators (e.g. delta Scutis) typically prefer <b>Amplitude</b>.
										        This has higher dynamic range (high and low peaks visible simultaneously), and we often want to
										        read off amplitudes from the plot.<br/><br/>
										        Alternatively, users doing asteroseismology on solar-like oscillators tend to prefer <b>PSD</b>.
										        This is because power density has a scaled axis that depends on the length of the observing time,
										        and is used when we are interested in noise levels (e.g. granulation) and are looking at damped oscillations.'>
							            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
						            </button>
					            </label>
				            </div>
				            
				            <!-- Lomb Scargle method -->
				            <div class="col-md-4 form-group">
					            <select class="form-control" id="p_ls_method" name="p_ls_method">
						            <option value="fast" selected>Fast (default)</option>
						            <option value="slow">Slow</option>
						            <option value="fastchi2">Fast Chi 2</option>
						            <option value="chi2">Chi 2</option>
					            </select>
					            <label for="p_ls_method" class="help-block">Lomb Scargle method
						            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
						                    data-title="Lomb Scargle method"
						                    data-content='By default this method uses the LombScargle <b>fast</b> method, which assumes a regular grid.
						                        If a regular grid of periods (i.e. an irregular grid of frequencies) it will use the <b>slow</b> method.
						                        If <code>nterms</code> > 1 is passed, it will use the <b>fastchi2</b> method for regular grids, and <b>chi2</b> for irregular grids.'>
							            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
						            </button>
					            </label>
				            </div>
				            
				            <!-- Oversample Factor -->
				            <div class="col-md-4 form-group">
					            <input type="text" class="form-control" id="p_ls_oversample" name="p_ls_oversample" placeholder="5 (default)">
					            <label for="p_ls_oversample" class="help-block">Oversample Factor <small>(to divide the frequency spacing by)</small>
						            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
						                    data-title="Oversample Factor"
						                    data-content='The frequency spacing (determined by the time baseline of the lightcurve) is divided
						                        by this factor, oversampling the frequency space. This parameter is identical to the
									            <code>samples_per_peak</code> parameter in <code>astropy.LombScargle()</code>.<br/><br/>
									            An oversampled spectrum (<code>oversample_factor</code> > 1) is useful for displaying the full details
										        of the spectrum, allowing the frequencies and amplitudes to be measured directly from the plot itself,
										        with no fitting required. This is recommended for most applications, with a value of 5 or 10.<br/><br/>
										        On the other hand, an <code>oversample_factor</code> of 1 means the spectrum is critically sampled, where
										        every point in the spectrum is independent of the others. This may be used when Lorentzians are to be
										        fitted to modes in the power spectrum, in cases where the mode lifetimes are shorter than the time-base
										        of the data (which is sometimes the case for solar-like oscillations). An <code>oversample_factor</code>
										        of 1 is suitable for these stars because the modes are usually fully resolved. That is, the power from
										        each mode is spread over a range of frequencies due to damping. Hence, any small error from measuring mode
										        frequencies by taking the maximum of the peak is negligible compared with the intrinsic linewidth of the modes.'>
							            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
						            </button>
					            </label>
				            </div>
				            
				            <!-- Number terms -->
				            <div class="col-md-4 form-group">
					            <input type="text" class="form-control" id="p_ls_nterms" name="p_ls_nterms" placeholder="1 (default)">
					            <label for="p_ls_nterms" class="help-block">Number of terms to use in the Fourier fit</label>
				            </div>
				            
				            <!-- Nyquist factor -->
				            <div class="col-md-4 form-group">
					            <input type="text" class="form-control" id="p_ls_nyquist" name="p_ls_nyquist" placeholder="1 (default)">
					            <label for="p_ls_nyquist" class="help-block">Multiple of the average Nyquist frequency <small>(overridden by maximum_frequency/minimum_period)</small></label>
				            </div>
				            
				            <!-- Frequency/Period -->
				            <div class="col-md-4 form-group">
					            <select class="form-control" id="p_ls_freq_period" name="p_ls_freq_period" data-placeholder="Select limit (optional)">
						            <option value="" selected></option>
						            <option value="frequency">Frequency</option>
						            <option value="period">Period</option>
					            </select>
					            <label for="p_ls_freq_period" class="help-block">Custom limit</label>
				            </div>
				            <div class="clearfix collapse visible hidden-sm p-lc-freq-section"></div>
				            <div class="col-md-6 form-group collapse p-lc-freq-section">
					            <div class="input-with-dropdown">
						            <input type="text" class="form-control" id="p_ls_frequencies_min" name="p_ls_frequencies_min" placeholder="Minimum">
						            <div class="input-group-btn">
							            <select class="form-control" id="p_ls_frequencies_unit" name="p_ls_frequencies_unit" title="Select frequency unit">
								            <option value="microhertz" title="1/μHz is used for period.">μHz</option>
								            <option value="1/day" selected title="Day is used for period.">1/day</option>
							            </select>
						            </div>
					            </div>
					            <label for="p_ls_frequencies_min" class="help-block">Limit Min</label>
				            </div>
				            <div class="col-md-6 form-group collapse p-lc-freq-section">
					            <div class="input-group">
						            <input type="text" class="form-control" id="p_ls_frequencies_max" name="p_ls_frequencies_max" placeholder="Maximum">
						            <span class="input-group-addon">1/day</span>
					            </div>
					            <label for="p_ls_frequencies_max" class="help-block">Limit Max</label>
				            </div>
			            </div>
		            </div>
		
		            <!-- Box Least Squares -->
		            <div class="col-md-12 collapse p-bls-section">
			            <div class="row">
				            <!--  "p_bls_frequency_factor" -->
				            <!-- Duration -->
				            <div class="col-md-6 form-group">
					            <input type="text" class="form-control" id="p_bls_duration" name="p_bls_duration" placeholder="0.25 (default)">
					            <label for="p_bls_duration" class="help-block">Duration</label>
				            </div>
				            <div class="col-md-6 form-group">
					            <input type="text" class="form-control" id="p_bls_frequency_factor" name="p_bls_frequency_factor" placeholder="10 (default)">
					            <label for="p_bls_frequency_factor" class="help-block">Frequency Factor</label>
				            </div>
				            <div class="col-md-6 form-group">
					            <div class="input-with-dropdown">
						            <input type="text" class="form-control" id="p_bls_minimum_period" name="p_bls_minimum_period" placeholder="Minimum">
						            <div class="input-group-btn">
							            <select class="form-control" id="p_bls_time_unit" name="p_bls_time_unit">
								            <option value="day" selected>day</option>
							            </select>
						            </div>
					            </div>
					            <label for="p_bls_minimum_period" class="help-block">Minimum Period</label>
				            </div>
				            <div class="col-md-6 form-group">
					            <div class="input-group">
						            <input type="text" class="form-control" id="p_bls_maximum_period" name="p_bls_maximum_period" placeholder="Maximum">
						            <span class="input-group-addon">day</span>
					            </div>
					            <label for="p_bls_maximum_period" class="help-block">Maximum Period</label>
				            </div>
			            </div>
		            </div>
	            </div>
	            
	            <!-- Seismology -->
	            <div class="row seismology-section">
		            <div class="col-md-12">
			            <div class="checkbox icheck-peterriver">
				            <input type="checkbox" id="isSeismology" name="is_seismology" />
				            <label for="isSeismology">Seismology</label>
				            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
				                    data-title="Seismology"
				                    data-content='Useful for estimating quick-look asteroseismic quantities.<br/><br/>
				                    By default, Seismology objects are built using the default periodogram.
				                    (It will also apply Normalize, Remove NaNs, Fill Gaps, and Flatten using default parameters.)<br/><br/>
				                    For further tune-ability, create a customized periodogram. This will also apply any selected lightcurve options.'>
					            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
				            </button>
			            </div>
		            </div>
	            </div>
             
            </div>
        </div>
        <hr class="col-md-11 col-md-offset-1 collapse lightcurve-section">
        
		<!-- VIEWING OPTIONS -->
        <div class="row collapse lightcurve-section">
            <label class="col-md-2 control-label" for="">Viewing Options</label>
            <div class="col-md-10">
                <!-- River plot -->
                <div class="row collapse fold-section">
                    <div class="col-md-12">
                        <div class="checkbox icheck-midnightblue">
                            <input type="checkbox" id="isRiverPlot" name="is_river_plot" />
                            <label for="isRiverPlot">Display river plot <small>(to see how periodic signals evolve over time)</small></label>
	                        <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
	                                data-title="River Plot"
	                                data-content='A river plot uses colors to represent the light curve values in chronological order,
	                                relative to the period of an interesting signal. Each row in the plot represents a full period cycle,
	                                and each column represents a fixed phase. This type of plot is often used to visualize
	                                <b>Transit Timing Variations (TTVs)</b> in the light curves of exoplanets.'>
		                        <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
	                        </button>
                        </div>
                    </div>
	                <div class="clearfix collapse riverplot-section visible hidden-sm" style="margin-bottom:15px;"></div>
	                
	                <div class="col-md-4 form-group collapse riverplot-section hidden">
		                <input type="text" class="form-control" id="river_plot_period" name="river_plot_period" placeholder="Period">
		                <label for="river_plot_period" class="help-block">Period <small>(at which to fold the light curve)</small></label>
	                </div>
	                <div class="col-md-4 form-group collapse riverplot-section hidden">
		                <input type="text" class="form-control" id="river_plot_time" name="river_plot_time" placeholder="Leave blank to use first time value">
		                <label for="river_plot_time" class="help-block">Epoch time <small>(phase mid point for plotting)</small></label>
	                </div>
	                <div class="col-md-6 form-group collapse riverplot-section">
		                <input type="text" class="form-control" id="river_plot_points" name="river_plot_points" placeholder="1 (default)">
		                <label for="river_plot_points" class="help-block">Number of points in each bin</label>
	                </div>
	                <div class="col-md-6 form-group collapse riverplot-section">
		                <input type="text" class="form-control" id="river_plot_phase_min" name="river_plot_phase_min" placeholder="-0.5 (default)">
		                <label for="river_plot_phase_min" class="help-block">Minimum phase to plot</label>
	                </div>
	                <div class="col-md-6 form-group collapse riverplot-section">
		                <input type="text" class="form-control" id="river_plot_phase_max" name="river_plot_phase_max" placeholder="0.5 (default)">
		                <label for="river_plot_phase_max" class="help-block">Maximum phase to plot</label>
	                </div>
	                <div class="col-md-6 form-group collapse riverplot-section">
		                <select class="form-control" id="river_plot_method" name="river_plot_method">
			                <option value="mean" title="Display the average value in each bin" selected>Mean (default)</option>
			                <option value="median" title="Display the average value in each bin">Median</option>
			                <option value="sigma" title="Display the average in the bin divided by the error in each bin, in order to show the data in terms of standard deviation">Sigma</option>
		                </select>
		                <label for="river_plot_method" class="help-block">River method</label>
	                </div>
                </div>

                <!-- Estimate CDPP -->
	            <div class="row">
		            <div class="col-md-6">
			            <div class="checkbox icheck-belizehole">
				            <input type="checkbox" id="isCDPP" name="is_cdpp" />
				            <label for="isCDPP">Estimate CDPP noise metric</label>
				            <button type="button" class="btn btn-link btn-xs" data-toggle="popover" data-placement="top" data-html="true"
				                    data-title="CDPP Noise Metric"
				                    data-content='A common estimate of the noise in a lightcurve is the scatter that
							        remains after all long term trends have been removed. This is the idea
						            behind the <b>Combined Differential Photometric Precision (CDPP) metric</b>.
							        The official Kepler Pipeline computes this metric using a wavelet-based
							        algorithm to calculate the signal-to-noise of the specific waveform of
							        transits of various durations. In this implementation, we use the
							        simpler "sgCDPP proxy algorithm" discussed by Gilliland et al
						            (2011ApJS..197....6G) and Van Cleve et al (2016PASP..128g5002V).<br/><br/>
							        The steps of this algorithm are:
						            <ol style="max-width:100%;width:400px;padding-left:15px;">
							            <li>Remove low frequency signals using a Savitzky-Golay filter with
							                window length <code>savgol_window</code> and polynomial order <code>savgol_polyorder</code>.</li>
							            <li>Remove outliers by rejecting data points which are separated from
							                the mean by <code>sigma</code> times the standard deviation.</li>
							            <li>Compute the standard deviation of a running mean with
							                a configurable window length equal to <code>transit_duration</code>.</li>
						            </ol>
						            We use a running mean (as opposed to block averaging) to strongly
							        attenuate the signal above 1/transit_duration whilst retaining
							        the original frequency sampling.  Block averaging would set the Nyquist
							        limit to 1/transit_duration.'>
					            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
				            </button>
			            </div>
		            </div>
		            <div class="clearfix collapse cdpp-section visible hidden-sm" style="margin-bottom:15px;"></div>
		
		            <div class="col-md-6 form-group collapse cdpp-section">
			            <div class="input-group">
				            <input type="text" class="form-control" id="cdpp_duration" name="cdpp_duration" placeholder="13 (default)">
				            <span class="input-group-addon">cadences</span>
			            </div>
			            <label for="cdpp_duration" class="help-block">Transit duration <small>(window length used to compute the running mean)</small></label>
		            </div>
		            <div class="col-md-6 form-group collapse cdpp-section">
			            <div class="input-group">
				            <input type="text" class="form-control" id="cdpp_window" name="cdpp_window" placeholder="101 (default)">
				            <span class="input-group-addon">cadences</span>
			            </div>
			            <label for="cdpp_window" class="help-block">Width of Savitsky-Golay filter (odd number)</label>
		            </div>
		            <div class="col-md-6 form-group collapse cdpp-section">
			            <input type="text" class="form-control" id="cdpp_polyorder" name="cdpp_polyorder" placeholder="2 (default, recommended)">
			            <label for="cdpp_polyorder" class="help-block">Polynomial order of the Savitsky-Golay filter</label>
		            </div>
		            <div class="col-md-6 form-group collapse cdpp-section">
			            <div class="input-group">
				            <input type="text" class="form-control" id="cdpp_sigma" name="cdpp_sigma" placeholder="5 (default)">
				            <span class="input-group-addon">sigma</span>
			            </div>
			            <label for="cdpp_sigma" class="help-block">Number of standard deviations to use for clipping outliers</label>
		            </div>
	            </div>
	            
	            <!-- Metadata -->
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
        <hr class="col-md-11 col-md-offset-1 collapse lightcurve-section">
		
		<!-- SUBMIT -->
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <input type="hidden" name="calc_date" id="calc_date" value="" />
                <input type="hidden" name="tracking_id" id="tracking_id" value="0" />
                <button type="submit" class="btn btn-fresh" id="run_button">Run Calculation</button>
            </div>
        </div>

    </form>

    <!-- Result list -->
    <div id="calculation-list"></div>
    <div id="calculation-logs"></div>
    <div id="calculation-result">
	    <div class="col-md-12" id="searchResultsTable"></div>
        <div class="col-md-12">
            <h4>Plots</h4>
            <div id="lcPlot"  title="Lightcurve plot"  style="width: 100%;"></div>
            <div id="pPlot"   title="Periodogram plot" style="width: 100%; margin-top: 10px;"></div>
            <div id="rPlot"   title="River plot"       style="width: 100%; margin-top: 10px;"></div>
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
	    <!-- Input table is inserted here -->
    </div>
</div>