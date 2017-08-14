<div class="col-md-12">
    <!-- Title -->
    <h1 class="page-header">New ATMOS Calculation</h1>

    <!-- Calculation Form -->
    <form enctype="multipart/form-data" class="form-horizontal" action="atmos/run" method="post" id="calculation-form">

        <div class="form-group">
            <label class="col-md-3 control-label" for="calc_name">Name</label>

            <div class="col-md-4">
                <input type="text" class="form-control" id="calc_name" name="calc_name" value="My New Calculation">
                <p class="help-block">Provide a name for this calculation</p>
            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <label class="col-md-3 control-label" for="planet_template">Planet Template</label>
            <div class="col-md-4">
                <select id="planet_template" name="planet_template" class="form-control" data-placeholder="Select Template">
                    <option value=""></option>
                    <option value="earth">Earth</option>
                    <option value="mars">Mars</option>
                    <option value="venus">Venus</option>
                </select>
            </div>
        </div>

        <div class="form-group hidden" id="planet_options">
            <label class="col-md-3 control-label" for="planet_template">Planet Options</label>
            <div class="col-md-9">
                <!-- Volcanism -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-pumpkin">
                            <input type="checkbox" disabled id="isVolcanism" />
                            <label for="isVolcanism">Volcanism</label>
                        </div>
                    </div>
                    <div class="col-md-6 form-group hidden" id="volcanism-section">
                        <div class="range-container">
                            <div class="input-group">
                                <input title="Volcanism" type="text" class="form-control" name="volcanism" value="1.00">
                                <span class="input-group-addon">g<sub>&oplus;</sub></span>
                            </div>
                            <div class="range">
                                <input title="Volcanism Slider" type="range" name="volcanism_slider" min="0.75" max="1.5" value="1" step="0.01">
                            </div>
                        </div>
                        <p class="help-block">Adjusts the gravity at the bottom of the atmospheric model. For large atmospheres this may be above the true "surface". (In units of cm/s^2)</p>
                    </div>
                </div>

                <!-- Methanogen Biosphere -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-carrot">
                            <input type="checkbox" disabled id="isMethane" />
                            <label for="isMethane">Methanogen Biosphere</label>
                        </div>
                    </div>
                    <div class="col-md-6 form-group hidden" id="methane-section">
                        <div class="range-container">
                            <div class="input-group">
                                <input title="Volcanism" type="text" class="form-control" name="methane" value="1.00">
                                <span class="input-group-addon">g<sub>&oplus;</sub></span>
                            </div>
                            <div class="range">
                                <input title="Volcanism Slider" type="range" name="methane_slider" min="0.75" max="1.5" value="1" step="0.01">
                            </div>
                        </div>
                        <p class="help-block">Adjusts the gravity at the bottom of the atmospheric model. For large atmospheres this may be above the true "surface". (In units of cm/s^2)</p>
                    </div>
                </div>

                <!-- Oxygenic Photosynthesis -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-greensea">
                            <input type="checkbox" disabled id="isOxygenPhotosynthesis" />
                            <label for="isOxygenPhotosynthesis">Oxygenic Photosynthesis</label>
                        </div>
                    </div>
                </div>

                <!-- Organic Sulfur Gases -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="checkbox icheck-sunflower">
                            <input type="checkbox" disabled id="isSulfur" />
                            <label for="isSulfur">Organic Sulfur Gases</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <label class="col-md-3 control-label" for="surface_gravity">Surface Gravity</label>
            <div class="col-md-4">
                <div class="range-container">
                    <div class="input-group">
                        <input title="Surface Gravity" type="text" class="form-control" name="surface_gravity" value="1.00">
                        <span class="input-group-addon">g<sub>&oplus;</sub></span>
                    </div>
                    <div class="range">
                        <input title="Surface Gravity Slider" type="range" name="surface_gravity_range" min="0.75" max="1.5" value="1" step="0.01">
                    </div>
                </div>
                <p class="help-block">Adjusts the gravity at the bottom of the atmospheric model. For large atmospheres this may be above the true "surface". (In units of cm/s^2)</p>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label" for="planet_radius">Planet Radius</label>
            <div class="col-md-4">
                <div class="range-container">
                    <div class="input-group">
                        <input title="Planet Radius" type="text" class="form-control" name="planet_radius" value="1.00">
                        <span class="input-group-addon">R<sub>&oplus;</sub></span>
                    </div>
                    <div class="range">
                        <input title="Planet Radius Slider" type="range" name="planet_radius_slider" min="0.5" max="2.0" value="1" step="0.01">
                    </div>
                </div>
                <p class="help-block">Changes the radius of the planet, affecting where the surface is defined.</p>
            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <div class="col-md-offset-3 col-md-9">
                <input type="hidden" name="tracking_id" id="tracking_id" value="0" />
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>

    </form>

    <!-- Result list -->
    <div id="calculation-list" class="hidden">
        <div class="col-md-12">
            <h2 class="page-header">Calculation Dashboard</h2>
        </div>
        <div class="col-md-12">
            <table id="calculation-table" class="table table-hover">
                <thead><tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Tools</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Results -->
    <div id="calculation-result" class="hidden">
        <div class="col-md-12">
            <h2 class="page-header">Calculation Results</h2>
        </div>

        <!-- Input data -->
        <div class="col-md-12">
            <h4>Input Data</h4>
            <table id="input-table" class="table table-striped">
                <thead>
                    <tr style="text-align: right;">
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Plots -->
        <div class="col-md-12">
            <h4>Plots</h4>
            <div class="col-md-6">
                <div id="vmrPlot" style="width: 100%;"></div>
            </div>
            <div class="col-md-6">
                <div id="tpPlot" style="width: 100%;"></div>
            </div>
        </div>
    </div>
</div>