<!-- Load CSS -->
<link href="assets/css/form.css" rel="stylesheet">
<link href="assets/css/bootstrapValidator.min.css" rel="stylesheet">

<!-- Title -->
<h1 class="page-header">New JWST Calculation</h1>

<!-- Form -->
<form enctype="multipart/form-data" class="form-horizontal" action="example/calculation/run" method="post" id="calculation-form">

    <div class="form-group">
        <label class="col-md-3 control-label" for="calcName">Name</label>

        <div class="col-md-9">
            <input type="text" class="form-control" id="calcName" name="calcName" value="My New Calculation">
            <p class="help-block">Provide a name for this calculation</p>
        </div>
    </div>

    <hr class="col-md-11 col-md-offset-1">

    <div class="form-group">
        <label class="col-md-3 control-label" for="" style="top: 35px;">Stellar Model</label>
        <div class="col-md-9">
            <div class="radio-chooser">
                <div class="radio-chooser-item">
                    <label class="radio-chooser-content" for="stellarModelPhoenix">
                        <input type="radio" name="stellarModel" id="stellarModelPhoenix" value="phoenix" />
                        <div class="radio-chooser-title">Phoenix Grid Models</div>
                    </label>
                </div>
                <div class="radio-chooser-item">
                    <label class="radio-chooser-content" for="stellarModelUser">
                        <input type="radio" name="stellarModel" id="stellarModelUser" value="user" />
                        <div class="radio-chooser-title">User Defined Stellar Spectrum</div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group hidden upload-stellar-section">
        <div class="col-md-3 col-md-offset-3">
            <div class="form-group">
                <div class="col-md-12">
                    <button class="btn btn-block niceFileBtn" type="button">Choose File</button>
                    <input type="file" id="starFile" name="starFile" style="display:none;padding:6px 1px;">
                    <p class="help-block text-center">Column 1: wavelength <br>Column 2: flux, no header</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="col-md-12 hidden">
                    <select id="starwunits" name="starwunits" class="form-control" data-placeholder="Select Wavelength Units">
                        <option value=""></option>
                        <option value="um">micron</option>
                        <option value="nm">nanometer</option>
                        <option value="cm">centimeter</option>
                        <option value="Angs">Angstrom</option>
                        <option value="Hz">Hertz</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="col-md-12 hidden">
                    <select id="starfunits" name="starfunits" class="form-control" data-placeholder="Select Flux Units">
                        <option value=""></option>
                        <option value="Jy">Jy</option>
                        <option value="W/m2/um">W/m2/um</option>
                        <option value="FLAM">erg/cm2/s/Angs</option>
                        <option value="erg/s/cm2/Hz">erg/s/cm2/Hz</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group hidden phoenix-section">
        <div class="col-md-3 col-md-offset-3">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="temp" name="temp" >
                        <span class="input-group-addon">&deg; Kelvin</span>
                    </div>
                    <p class="help-block">Stellar temperature</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" class="form-control" id="metal" name="metal" >
                    <p class="help-block">Stellar metallicity</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" class="form-control" id="logg" name="logg" >
                    <p class="help-block">Stellar log g</p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group hidden phoenix-section upload-stellar-section">
        <label class="col-md-3 control-label" for="mag">Magnitude</label>
        <div class="col-md-4 form-inline">
            <input type="text" class="form-control" id="mag" name="mag" style="width: 70%;">
            <div class="input-group-btn" style="width: 20%;left: -8px;display: inline-block;">
                <select id="ref_wave" name="ref_wave" class="form-control" data-placeholder="Select Magnitude Units">
                    <!--<option value=""></option>-->
                    <option value="1.26">J</option>
                    <option value="1.60">H</option>
                    <option value="2.22">K</option>
                </select>
            </div>
            <p class="help-block">Magnitude of Stellar Target</p>
            <style>
                .phoenix-section .select2-selection {
                    border-top-left-radius: 0;border-bottom-left-radius: 0;
                }
            </style>
        </div>
    </div>

    <hr class="col-md-11 col-md-offset-1">

    <div class="form-group">
        <label class="col-md-3 control-label" for="" style="top: 35px;">Planet Model</label>
        <div class="col-md-9">
            <div class="radio-chooser">
                <div class="radio-chooser-item">
                    <label class="radio-chooser-content" for="planetModelConstant">
                        <input type="radio" name="planetModel" id="planetModelConstant" value="constant" />
                        <div class="radio-chooser-title">Constant Temp / Radius Model</div>
                    </label>
                </div>
                <div class="radio-chooser-item">
                    <label class="radio-chooser-content" for="planetModelUser">
                        <input type="radio" name="planetModel" id="planetModelUser" value="user" />
                        <div class="radio-chooser-title">User Defined Planet Spectrum</div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group hidden upload-planet-section">
        <div class="col-md-3 col-md-offset-3">
            <div class="form-group">
                <div class="col-md-12">
                    <button class="btn btn-block niceFileBtn" type="button">Choose File</button>
                    <input type="file" id="planFile" name="planFile" style="display:none;padding:6px 1px;">
                    <p class="help-block text-center">Column 1: wave or time <br>Column 2: Model, no header</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="col-md-12 hidden">
                    <select id="planwunits" name="planwunits" class="form-control" data-placeholder="Select Wave or Time Units">
                        <option value=""></option>
                        <option value="sec">seconds</option>
                        <option value="um">micron</option>
                        <option value="nm">nanometer</option>
                        <option value="cm">centimeter</option>
                        <option value="Angs">Angstrom</option>
                        <option value="Hz">Hertz</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="col-md-12 hidden">
                    <select id="planfunits" name="planfunits" class="form-control" data-placeholder="Select Planet Model Units">
                        <option value=""></option>
                        <option value="rp^2/r*^2" selected>(R<sub>p</sub>/R<sub>*</sub>)^2 (primary)</option>
                        <option value="fp/f*">F<sub>p</sub>/F<sub>*</sub> (secondary/phase curve)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group hidden constant-transit-section">
        <div class="col-md-3 col-md-offset-3">
            <div class="form-group">
                <div class="col-md-12">
                    <select id="constplanfunits" name="constplanfunits" class="form-control" data-placeholder="Select Planet Model Units">
                        <option value=""></option>
                        <option value="primary" selected>Primary: (R<sub>p</sub>/R<sub>*</sub>)^2</option>
                        <option value="secondary" disabled>Secondary: F<sub>p</sub>/F<sub>*</sub></option>
                        <option value="phase" disabled>Phase Curve: F<sub>p</sub>/F<sub>*</sub></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3 primary-transit-section secondary-transit-section">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="depth" name="depth">
                        <span class="input-group-addon">(R<sub>p</sub>/R<sub>*</sub>)<sup>2</sup></span>
                    </div>
                    <p class="help-block primary-transit-section">Wavelength-independent transit depth</p>
                    <p class="help-block secondary-transit-section hidden">Planet/Star Radius Ratio</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 secondary-transit-section hidden">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="ptemp" name="ptemp" >
                        <span class="input-group-addon">&deg; Kelvin</span>
                    </div>
                    <p class="help-block">Planet temperature</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 hidden">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" class="form-control" id="pradius" name="pradius" >
                        <span class="input-group-addon">R <sub>J</sub></span>
                    </div>
                    <p class="help-block">Planet Radius</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="col-md-11 col-md-offset-1">

    <div class="form-group">
        <label class="col-md-3 control-label" for="transit_duration">Transit Duration</label>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" id="transit_duration" name="transit_duration">
                <span class="input-group-addon">seconds</span>
            </div>
            <p class="help-block">For phase curves, will derive duration of phase from input file.</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="fraction">In:Out Ratio</label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="fraction" name="fraction" >
            <p class="help-block">Fraction of time spent in versus out of transit (in/out)</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="numtrans">Number of Transits</label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="numtrans" name="numtrans" >
            <p class="help-block">Number of transits or phase observations</p>
        </div>
    </div>

    <hr class="col-md-11 col-md-offset-1">

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <h4>JWST Instrument Modes</h4>
        </div>
        <div class="col-md-6 col-md-offset-3 col-sm-offset-1">
            <!--<img id="full" alt="-" width="660" height="426" src="../static/img/pec_res.jpg" style="left: -63px;position: relative;">-->
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="instrument">Instrument</label>
        <div class="col-md-6">
            <select id="instrument" name="instrument" class="form-control" data-placeholder="Select Instrument">
                <option value=""></option>
                <option value="MIRI">MIRI Low Resolution Spectroscopy</option>
                <option value="NIRSpec">NIRSpec Bright Object Time Series</option>
                <option value="NIRCam">NIRCam Grism Time Series</option>
                <option value="NIRISS">NIRISS Single Object Slitless Spectroscopy</option>
            </select>
        </div>
    </div>

    <div class="form-group hidden" id="instrument-mode-section">
        <label class="col-md-3 control-label" for="mirimode">Mode</label>
        <div class="col-md-4" id="MIRI">
            <select id="mirimode" name="mirimode" class="form-control" data-placeholder="Select MIRI Mode">
                <option value=""></option>
                <option value="lrsslitless">Slitless LRS</option>
                <option value="lrsslit">Slit LRS</option>
            </select>
        </div>

        <div class="col-md-6" id="NIRSpec">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <select id="nirspecmode" name="nirspecmode" class="form-control" data-placeholder="Select NIRSpec Mode">
                                <option value=""></option>
                                <option value="g140mf070lp">G140 R=1000 f070lp</option>
                                <option value="g140hf070lp">G140 R=2700 f070lp</option>
                                <option value="g140mf100lp">G140 R=1000 f100lp</option>
                                <option value="g140hf100lp">G140 R=2700 f100lp</option>
                                <option value="g235mf170lp">G235 R=1000 f170lp</option>
                                <option value="g235hf170lp">G235 R=2700 f170lp</option>
                                <option value="g395mf290lp">G395 R=1000 f290lp</option>
                                <option value="g395hf290lp">G395 R=2700 f290lp</option>
                                <option value="prismclear">Prism R=100 No filter</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <select id="nirspecsubarray" name="nirspecsubarray" class="form-control" data-placeholder="Select NIRSpec Subarray">
                                <option value=""></option>
                                <option value="sub1024a">S1600A1 SUB1024A</option>
                                <option value="sub1024b">S1600A1 SUB1024B</option>
                                <option value="sub2048">S1600A1 SUB2048</option>
                                <option value="sub512">S1600A1 SUB512</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6" id="NIRCam">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <select id="nircammode" name="nircammode" onchange="showForm()" class="form-control" data-placeholder="Select NIRCam Mode">
                                <option value=""></option>
                                <option value="f322w2">F322W2, 2.7-4 um</option>
                                <option value="f444w">F444W, 4-5 um</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <select id="nircamsubarray" name="nircamsubarray" onchange="showForm()" class="form-control" data-placeholder="Select NIRCam Subarray">
                                <option value=""></option>
                                <option value="subgrism64">SUBGRISM64, 4 outs(tframe=0.34)</option>
                                <option value="subgrism128">SUBGRISM128, 4 outs(tframe=0.67)</option>
                                <option value="subgrism256">SUBGRISM258, 4 outs(tframe=1.34)</option>
                                <option value="subgrism64 (noutputs=1)">SUBGRISM64, 1 out(tframe=1.3)</option>
                                <option value="subgrism128 (noutputs=1)">SUBGRISM128, 1 out(tframe=2.6)</option>
                                <option value="subgrism256 (noutputs=1)">SUBGRISM258, 1 out(tframe=5.2)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4" id="NIRISS">
            <select id="nirissmode" name="nirissmode" onchange="showForm()" class="form-control" data-placeholder="Select NIRISS Mode">
                <option value=""></option>
                <option value="substrip96">Substrip 96</option>
                <option value="substrip256">Substrip 256</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="optimize">Number of Groups per Integration</label>
        <div class="col-md-4">
            <input type="text" class="form-control" id="optimize" name="optimize" value="optimize" >
            <p class="help-block">Recommended to compute optimal groups per integration first (type "optimize"). But, you can also input any number from 2-65536. </p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="satlevel">Percent Fullwell</label>
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" id="satlevel" name="satlevel" >
                <span class="input-group-addon">%</span>
            </div>
            <p class="help-block">Percent fullwell of electrons.</p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label" for="" style="top: 35px;">Noise Floor</label>
        <div class="col-md-9">
            <div class="radio-chooser">
                <div class="radio-chooser-item">
                    <label class="radio-chooser-content" for="noiseModelConstant">
                        <input type="radio" name="noiseModel" id="noiseModelConstant" value="constant-noise" />
                        <div class="radio-chooser-title">Constant Minimum Noise</div>
                    </label>
                </div>
                <div class="radio-chooser-item">
                    <label class="radio-chooser-content" for="noiseModelUser">
                        <input type="radio" name="noiseModel" id="noiseModelUser" value="user" />
                        <div class="radio-chooser-title">User Defined Noise Model</div>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group hidden upload-noise-section">
        <div class="col-md-3 col-md-offset-3">
            <button class="btn btn-block niceFileBtn" type="button">Choose File</button>
            <input type="file" id="noiseFile" name="noiseFile" style="display:none;padding:6px 1px;">
            <p class="help-block">Column 1: Wavelength <br>Column 2: Noise Floor (ppm)</p>
        </div>
    </div>

    <div class="form-group hidden constant-noise-section">
        <div class="col-md-3 col-md-offset-3">
            <div class="input-group">
                <input type="text" class="form-control" id="noisefloor" name="noisefloor" value="0">
                <span class="input-group-addon">ppm</span>
            </div>
            <p class="help-block">Constant Minimum Noise</p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>

</form>

<!-- Leave Javascript for last -->
<script type="text/javascript" src="assets/js/bootstrapValidator.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
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

        /* Form Validation */
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

		var validationConfigFields = {
			calcName: {
				validators: {
					notEmpty: {
						message: 'Calculation name is required'
					}
				}
			},
			stellarModel: {
				validators: {
					notEmpty: {
						message: 'Stellar model is required'
					}
				}
			},
			starFile: {
				excluded: function () {
					return !$("#stellarModelUser").is(":checked");
				},
				validators: {
					notEmpty: {
						message: 'Stellar Spectrum file is required'
					},
					file: {
						type: 'text/plain',
						message: 'Please choose a text file'
					},
					checkFileColumns: {}
				}
			},
			starwunits: {
				validators: {
					notEmpty: {
						message: 'Stellar wavelength units are required'
					}
				}
			},
			starfunits: {
				validators: {
					notEmpty: {
						message: 'Stellar flux units are required'
					}
				}
			},
			temp: {
				validators: {
					notEmpty: {
						message: 'Stellar temperature is required'
					},
					numeric: {
						message: 'Stellar temperature must be a number'
					},
					between: {
						min: 2300,
						max: 12000,
						message: 'Stellar temperature must be between 2300K and 12000K'
					}
				}
			},
			metal: {
				validators: {
					notEmpty: {
						message: 'Stellar metallicity is required'
					},
					numeric: {
						message: 'Stellar metallicity must be a number'
					},
					between: {
						min: -4,
						max: 1,
						message: 'Stellar metallicity must be between -4.0 and +1.0'
					}
				}
			},
			logg: {
				validators: {
					notEmpty: {
						message: 'Stellar log g is required'
					},
					numeric: {
						message: 'Stellar log g must be a number'
					},
					between: {
						min: 0,
						max: 6,
						message: 'Stellar log g must be between 0.0 and 6.0'
					}
				}
			},
			mag: {
				validators: {
					notEmpty: {
						message: 'Magnitude is required'
					},
					numeric: {
						message: 'Magnitude must be a number'
					}
				}
			},
			planetModel: {
				validators: {
					notEmpty: {
						message: 'Planet model is required'
					}
				}
			},
			planFile: {
				excluded: function () {
					return !$("#planetModelUser").is(":checked");
				},
				validators: {
					notEmpty: {
						message: 'Planet Spectrum file is required'
					},
					file: {
						type: 'text/plain',
						message: 'Please choose a text file'
					},
					checkFileColumns: {}
				}
			},
			planwunits: {
				validators: {
					notEmpty: {
						message: 'Planet time/wave units are required'
					}
				}
			},
			planfunits: {
				validators: {
					notEmpty: {
						message: 'Planet model units are required'
					}
				}
			},
			constplanfunits: {
				validators: {
					notEmpty: {
						message: 'Planet model units are required'
					}
				}
			},
			depth: {
				validators: {
					notEmpty: {
						message: 'Wavelength-independent transit depth is required'
					},
					numeric: {
						message: 'Wavelength-independent transit depth must be a number'
					},
					greaterThan: {
						value: 0,
						message: 'Wavelength-independent transit depth must be a positive number'
					}
				}
			},
			transit_duration: {
				validators: {
					numeric: {
						message: 'Transit duration must be a number'
					},
					greaterThan: {
						value: 60,
						message: 'Transit duration must be in seconds (greater than 60 seconds)'
					}
				}
			},
			fraction: {
				validators: {
					notEmpty: {
						message: 'In:out ratio is required'
					},
					numeric: {
						message: 'In:out ratio must be a number'
					},
					greaterThan: {
						value: 0,
						inclusive: false,
						message: 'In:out ratio must be greater than zero'
					}
				}
			},
			numtrans: {
				validators: {
					notEmpty: {
						message: 'Transit number is required'
					},
					numeric: {
						message: 'Must be a number of transits'
					},
					integer: {
						message: 'Must be an integer number of transits'
					},
					greaterThan: {
						value: 0,
						inclusive: false,
						message: 'Transit number must be greater than 0'
					}
				}
			},
			instrument: {
				validators: {
					notEmpty: {
						message: 'Instrument selection is required'
					}
				}
			},
			mirimode: {
				validators: {
					notEmpty: {
						message: 'Please select a MIRI Mode'
					}
				}
			},
			nirspecmode: {
				validators: {
					notEmpty: {
						message: 'Please select a NIRSpec Mode'
					}
				}
			},
			nirspecsubarray: {
				validators: {
					notEmpty: {
						message: 'Please select a NIRSpec Subarray'
					}
				}
			},
			nircammode: {
				validators: {
					notEmpty: {
						message: 'Please select a NIRCam Mode'
					}
				}
			},
			nircamsubarray: {
				validators: {
					notEmpty: {
						message: 'Please select a NIRCam Subarray'
					}
				}
			},
			nirissmode: {
				validators: {
					notEmpty: {
						message: 'Please select a NIRISS Mode'
					}
				}
			},
			optimize: {
				validators: {
					notEmpty: {
						message: 'Number of transits is required'
					},
					choiceTextNumber: {
						text: 'optimize',
						min: 2,
						max: 65536,
						message: '' // see custom validation rule
					}
				}
			},
			satlevel: {
				validators: {
					notEmpty: {
						message: 'Percent fullwell of electrons is required'
					},
					between: {
						min: 0,
						max: 100,
						message: 'Must be a percentage between 0 and 100'
					},
					greaterThan: {
						value: 0,
						inclusive: false,
						message: 'Percent fullwell of electrons may not be zero'
					}
				}
			},
			noiseModel: {
				validators: {}
			},
			noiseFile: {
				excluded: function () {
					return !$("#noiseModelUser").is(":checked");
				},
				validators: {
					file: {
						type: 'text/plain',
						message: 'Please choose a text file'
					},
					checkFileColumns: {}
				}
			},
			noisefloor: {
				validators: {
					numeric: {
						message: 'Noise floor must be a number'
					},
					greaterThan: {
						value: 0,
						message: 'Noise floor must be greater than 0'
					}
				}
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
		});
	});
</script>