<!-- Load CSS -->
<link href="css/form.css" rel="stylesheet">
<link href="css/bootstrapValidator.min.css" rel="stylesheet">

<div class="col-md-12">
    <!-- Title -->
    <h1 class="page-header">New ATMOS Calculation</h1>

    <!-- Form -->
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
                <p class="help-block">This is some random text. Please change me!!</p>
            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <label class="col-md-3 control-label" for="surface_gravity">Surface Gravity</label>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="surface_gravity" name="surface_gravity" value="1.00">
                    <span class="input-group-addon">g<sub>&oplus;</sub></span>
                </div>
                <div class="range">
                    <input title="Surface Gravity Slider" type="range" name="surface_gravity_range" min="0.75" max="1.5" value="1" id="surface_gravity_range" step="0.01">
                </div>
                <p class="help-block">Adjusts the gravity at the bottom of the atmospheric model. For large atmospheres this may be above the true "surface". (In units of cm/s^2)</p>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label" for="planet_radius">Planet Radius</label>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="planet_radius" name="planet_radius" value="1.00">
                    <span class="input-group-addon">R<sub>&oplus;</sub></span>
                </div>
                <div class="range">
                    <input title="Planet Radius Slider" type="range" name="planet_radius_slider" min="0.5" max="2.0" value="1" id="planet_radius_slider" step="0.01">
                </div>
                <p class="help-block">Changes the radius of the planet, affecting where the surface is defined.</p>
            </div>
        </div>

        <hr class="col-md-11 col-md-offset-1">

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
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

    <div id="calculation-result" class="hidden">
        <div class="col-md-12">
            <h2 class="page-header">Calculation Results</h2>
        </div>
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
    </div>
</div>

<!-- Leave Javascript for last -->
<script type="text/javascript" src="js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="js/atmos/atmos-calculation.js"></script>