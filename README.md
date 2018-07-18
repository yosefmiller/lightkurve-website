## About this template

A ready-to-use EMAC model implementation template, with real-time validation, multiple concurrent calculations, persistent session/results, and piped form-data to another language script (eg. python).  

- The `Dockerfile` extends the `balr/php7-fpm-nginx` base image, with a custom `nginx-site.conf` file to point all non-existing public files to `index.php`, which handles routing using the Klein router.  
- `javascript` and `jquery` are used for front-end validation and dynamic form controls.  
- `php` is used to handle forms and keep track of users and their calculations.  
- `python` (or similar) processes the piped input data and outputs a prespecified file when completed.

This template makes use of the _Klein.php_ router. Documentation for Klein is available [on GitHub](https://github.com/klein/klein.php).  
This template also uses a few client-side libraries: [Bootstrap](http://getbootstrap.com/), [jQuery](https://jquery.com/), [Select2](http://select2.github.io/), [Bootstrap Validator](https://github.com/nghuuphuoc/bootstrapvalidator/tree/v0.5.2)

---
## Server Configuration

#### Skip this if using the Docker configuration.  
Install **PHP** > 5.3 if not done already.

#### Nginx
Modify your nginx configuration file. Typical location:

    sudo nano /etc/nginx/sites-available/example.com

Add a new location, relative to document root:

````text
location /path/to/directory/ {
    try_files $uri $uri/ /path/to/directory/index.php?$args;
}
````

#### Apache
Add a `.htaccess` file to project directory:

````text
# Configure Klein routing:
<IfModule mod_rewrite.c>
    Options -MultiViews
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule (.*) /path/to/directory/index.php [L]
</IfModule>
````

---
## Layout Customization

##### Files
- `pages/partials/master-layout.php`: _Master layout_ -- Every page first goes here. It loads the various layout components.
- `pages/partials/main-header.php`: _Main header_  
- `pages/partials/sub-header.php`: _Sub header_ -- See below for customization.  
- `pages/partials/sub-navigation.php`:  _Sub navigation_ -- Populated using `pages/models/sub-navigation.json`.  
- `pages/partials/sidebar.php`: _Sidebar_  
- `pages/partials/footer.php`: _Footer_  
- `css/layout.css`: Headers, navigation, sidebar, footer, etc.  
- `css/components.css`: 3D buttons, arrowed labels, image captions, and story sections (for articles, blogs)  
- `css/form.css`: Radio chooser, validation, range slider, and loading spinner  
- `css/site.css`: _Site-specific customizations_ -- color theme and background images.  
- `js/main.js`: Search bar, navigation dropdown, sidebar toggle, and Select2 initialization
- `img`: Images and logos. Store site-specific images in a sub-directory, eg. `img/emac`

###### pages/partials/sub-header.php

````html
<div class="container">
    <div class="row">
        <!-- Remember to correct the bootstrap column widths appropriately. -->
        <!-- Default: left. To align right, add class `nasa__sub-name-right` -->
        <div class="nasa__sub-name col-md-3">
            <div><a href="/">Code 690</a></div>
        </div>
        
        <!-- Default: right. To align left, add class `nasa__sub-logo-left` -->
        <div class="nasa__sub-logo col-md-3">
            <img src="img/emac/emac_logo_cropped.jpg" alt="EMAC" />
        </div>
        
        <!-- Leave this last so the others are displayed in the right order. -->
        <div class="nasa__sub-name col-md-6">
            <div>
                <a href="/">
                    <!-- Add a `div` for a subtitle, like so: -->
                    <div>Sciences and Exploration Directorate</div>
                    Solar System Exploration Division
                </a>
            </div>
        </div>
    </div>
</div>
````

---
## Configure Routing
###### index.php
````php
$klein->respond('/somepage', function ($req, $res, $service) {
    $service->pageTitle = 'A clever page title goes here | EMAC';   // (required) page/tab title
    $service->isMiniHeader = true;                      // (optional) hides main-header and sub-navigation, shrinks sub-header
    $service->isHiddenSidebar = true;                   // (optional) hides sidebar
    $service->isHiddenSubNav = true;                    // (optional) hides sub-navigation
    $service->isForm = true;                            // (optional) includes necessary scripts and styles for calculation forms
    $service->customJSFile = "js/some-calculation.js";  // (optional) custom javascript file, useful for form pages
    $service->someVariable = json_decode(file_get_contents("pages/models/some-variable.json"), true);
                                                        // (optional) passes custom data to the page (see examples for usage)
    $service->sidebarPath = 'examples/partials/sidebar.php';    // (optional) custom sidebar
    $service->render('pages/some-page.php');            // (required) renders view inside layout
});
````

###### pages/some-page.php
````php
<div class="col-md-12">
    <h1>Welcome to some new page! Next up: create a form to run some mind-blowing calculations!</h1>
</div>
````

---
## Create a form

Make sure to set the `isForm` option to true.  

###### pages/some-calculation.php
````html
<!-- Title -->
<h1 class="page-header">New Calculation</h1>

<!-- Calculation Form -->
<form enctype="multipart/form-data" class="form-horizontal" action="" method="post" id="calculation-form">
    <!-- ADD ALL INPUTS HERE -->
    
    <!-- At the end of the form: -->
    <div class="form-group">
        <div class="col-md-offset-3 col-md-9">
            <input type="hidden" name="calc_date" id="calc_date" value="" />
            <input type="hidden" name="tracking_id" id="tracking_id" value="0" />
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>

<!-- Result list -->
<div id="calculation-list"></div>
<div id="calculation-result">
    <div class="col-md-12">
        <h4>Plots</h4>
        <div class="col-md-6"><div id="vmrPlot" style="width: 100%;"></div></div>
        <div class="col-md-6"><div id="tpPlot" style="width: 100%;"></div></div>
    </div>
</div>
````

---
## Validate the form
Set the rules! It's all about creating some simple json. Validators are executed in order.  
This template uses an old open-source version of the [Form Validation](http://formvalidation.io) plugin called [Bootstrap Validator](https://github.com/nghuuphuoc/bootstrapvalidator).  
Here's an example configuration. Many more validators are available.  
You can even code a custom validator. See the Pandexo form for an example, such as checking files for valid columns of numbers.  

###### assets/js/some-calculation.js
````js
$.FORM_PREFIX = "/";
$.FORM_CHECK_INTERVAL = 3000;
$.validationConfigFields = {
    some_input_name: {
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
    }
};

/* Display the plots and data according to tool's specifications */
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

/* Must call these to get started */
$.initValidation();
$.initCalculationList();
````

---
## Running Calculations

This is how it works:  
1. Submission is handled by the `newCalculation` function, which:<br/> Creates a new list item via `createListItem` function.<br/>Submits the form using `POST` to `/run`.<br/>
2. Javascript identifies and keeps track of each calculation with a number, stored in the `trackingId` cookie.<br/>Call `getCurrentTrackingID()` for the latest calculation number.<br/>Call `setNextTrackingID()` to set and return a new id for the pending calculation.
3. The user is tracked via HTML Cookies or IP Address:<br/>Javascript requests a user identifier by setting the `needcookie` cookie.<br/>PHP sets the `userId` cookie with a unique generated id.<br/>If neither are set (cookies are disabled), then the user's IP address is used instead.<br/>This identifier can be accessed in PHP by calling `$userID = $service->getUserId->__invoke($res);` from within a route.
4. PHP processes the form:<br/>Validates the form to prevent hacks.<br/>Executes the given Python script in the background, and passes it the form data as JSON.<br/>Responds to Javascript with JSON (status 'running'), as discussed soon.
5. Javascript periodically pings PHP (at `/check/<tracking_id>`), listening for the creation of a response file.
6. Python outputs a response file with calculation results.
7. Javascript marks the calculation as complete, and displays the results when asked.

#### Response Data:

All server responses are received by the Javascript `handleCalculation(response)` function.<br/>
Response must have any of the following formats:<br/>

###### Always sent by `/run` and, if calculation not complete, by `/check/<tracking_id>`:
````json
{
    "status": "running",
    "input": {
      "tracking_id": "<user_id>_<tracking_id>"
    }
}
````

###### Sent by `/run` for validation error, and outputted by Python code for calculation errors:
````json
{
    "status": "error",
    "message": "Replace with some readable error message",
    "input": {
      "tracking_id": "<user_id>_<tracking_id>"
    }
}
````

###### Outputted by Python code, if successful, to `/python/outputs/<calculation_id>_response.json`:
````json
{
    "status": "success",
    "vmr_file": "python/outputs/<calculation_id>_profile2.pt",
    "tp_file": "python/outputs/<calculation_id>_profile2.pt",
    "input": "<all_form_data_object>"
}
````

#### Python code and output file generation:
Here's what is needed for php to detect outputted file.<br/>
Remember to properly set folder permissions for the `/python/outputs` directory to allow Python to write files.

###### some-calculation.py
````python
# Load json data
import sys, json
form_data = json.loads(sys.argv[1])

# Store data into individual variables
calc_name = form_data["calc_name"]
planet_template = form_data["planet_template"]
surface_gravity = form_data["surface_gravity"]
planet_radius = form_data["planet_radius"]

# Run calculations...
# Create plot files...
# Get calculation results...

# Prepare response
response = {}
response["input"] = form_data
response["status"] = "success"
response["vmr_file"] = "python/outputs/profile2.pt"
response["tp_file"] = "python/outputs/profile2.pt"
response = json.dumps(response)

# Save data
output_file_name = "python/outputs/" + form_data["tracking_id"] + "_response.json"
with open(output_file_name, "w") as f:
    f.write(response)
````

#### Display results:
Customize the `plotResult` Javascript function to display the plots and data according to your specifications.

#### Discover something amazing with calculation results...