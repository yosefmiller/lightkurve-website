# How to use this template

This template makes use of the _Klein.php_ router.

Documentation for Klein.php is available [on GitHub](https://github.com/klein/klein.php).

I provide information here for quick and simple reference.

## Server Configuration

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

<br/>

---

## Template Configuration

All routing is handled by the `index.php` file.

##### Layout
The main layout is handled by the `pages/partials/master-layout.php` file.

###### index.php

````php
/* Configure main layout (for all pages): */
$klein->respond(function ($req, $res, $service, $app) {
    $service->layout('pages/partials/master-layout.php');
});
````

###### pages/partials/master-layout.php

````html
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $this->escape($this->pageTitle); ?></title>
    <base href="/path/to/directory/" /><!-- TODO: change this appropriately -->
    <link href="assets/css/emac/site.css" rel="stylesheet">
</head>
<body>
    <?= $this->partial("pages/partials/header.php"); ?>
    <?= $this->yieldview(); ?><!-- The rendered file will be inserted here -->
    <?= $this->partial("pages/partials/footer.php"); ?>
</body>
</html>
````

##### Pages

Configure the new route in `index.php` file:

````php
/* Main routing: */
$klein->respond('/home', function ($req, $res, $service) {
    $service->pageTitle = 'Home Page - Exoplanet Modeling and Analysis Center (EMAC)';
    $service->render('pages/home.php');
});
````

And redirect to the home page:

````php
$klein->respond('/', function ($req, $res, $service) {
    $res->redirect('home')->send();
});
````

Create the actual page:

###### pages/home.php

````html
<div class="col-md-12">
    <h1>Welcome the EMAC home page!</h1>
</div>
````


##### Data

_You can use this method to pass information to the page._
<br/>
Example of using json to generate content:

###### index.php

````php
/* Place within the route, or global route */ 
$service->someVariable = json_decode(file_get_contents("pages/models/some-variable.json"), true);
````

###### pages/models/some-variable.json

````json
{
  "1": {
    "first": "John",
    "last": "Doe"
  },
  "2": {
    "first": "Jim",
    "last": "Smith"
  }
}
````

###### pages/some-page.php

````php
<?php foreach($this->someVariable as $i => $foo){ ?>
    <div id="name-<?= $i ?>">
        Hello <?= $foo["first"] ?> <?= $foo["last"] ?>
    </div>
<?php } ?>
````

<br/>

---
# Layout Customization

### Sub-header

##### Mini-header
To display just a _mini-header_, simply set the `isMiniHeader` value to the route (before `render`):

###### index.php

````php
$klein->respond('/home', function ($req, $res, $service) {
    $service->pageTitle = 'Exoplanet Modeling and Analysis Center - NASA/GSFC';
    $service->isMiniHeader = true;
    $service->render('pages/emac-home.php');
});
````

##### Site information
Here's how to change the information about the site _(see comments in code)_:

###### pages/partials/sub-header.php

````html
...
<!-- NASA SUB-SITE INFORMATION -->
<div class="container">
    <div class="row">
        <!-- Remember to correct the bootstrap column widths appropriately. -->
        <!-- Default: left. To align right, add class `nasa__sub-name-right` -->
        <div class="nasa__sub-name col-md-3">
            <div><a href="/">Code 690</a></div>
        </div>
        
        <!-- Default: right. To align left, add class `nasa__sub-logo-left` -->
        <div class="nasa__sub-logo col-md-3">
            <img src="assets/img/emac/emac_logo_cropped.jpg" alt="EMAC" />
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
<!-- END NASA SUB-SITE INFORMATION -->
...
````

##### Sub Navigation
To remove, delete the reference to the _partial_ in `pages/partial/sub-header.php`
<br/>
To update the data, modify `pages/models/sub-navigation.json` file appropriately.

### Sidebar

##### To Remove

To remove the sidebar altogether, simply set the `isHiddenSidebar` value to the route (before `render`)

###### index.php

````php
$klein->respond('/home', function ($req, $res, $service) {
    $service->pageTitle = 'Exoplanet Modeling and Analysis Center - NASA/GSFC';
    $service->isHiddenSidebar = true;
    $service->render('pages/emac-home.php');
});
````

##### Change Style

Refer to `examples/partials/sidebar.php` for a navigation bar which includes dropdowns.

<br/>

---
# Directory Structure

###### index.php
It all begins here. Handles all the routing.

### API

Hosts the `Klein.php` router. The customized _PSR-0_ `autoloader.php` script imports the Klein api.

### Examples
Stuff to refer to.

### Assets

#### CSS

###### bootstrap.css
> [Bootstrap](http://getbootstrap.com/) is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.

###### select2.min.css, select2-bootstrap-theme.min.css
> [Select2](http://select2.github.io/) is the jQuery replacement for select boxes.

###### main.css
This contains styling for all the layout components: headers, sidebar, footer, etc.
<br/>
It has everything but the main content.

###### style.css
This contains styling for components that may be useful within the main content.
<br/>
It contains 3D buttons, arrowed labels, image captions, and story sections (for articles, blogs).
<br/>
Refer to `examples/pages/home-main.php` for an example of story sections and image captions.
<br/>
Also, the 404 page provides a very basic example.

###### form.css
This contains styling for calculation forms. Include this file for such a page.

###### emac/site.css
This contains site-specific styling and color settings.
<br/>
_Change this file_ according to the site's color theme and desired background images.

#### Fonts
Glyphicons and the Lato font.

#### Img
Images and logos. Site specific images belong in a sub-directory, eg. `img/emac`

#### JS
Javascript libraries such as _bootstrap_, _jquery_ and _select2_.

###### main.js
Handles headers, navigation dropdowns, search bar, etc.

### Pages
###### pages
The main content for each page goes in here, ending in `.php`.

###### pages/models
Data files go here, in `json` format. Refer to the data section on how to use.

###### pages/partials
Layout components go here. `master-layout.php` pulls the `main-header.php`, `sub-header.php` (which pulls `sub-navigation.php`), `sidebar.php`, and `footer.php`.

<br/>

---
# How to create a form

#### Define the route:

###### index.php
````php
$klein->respond('/?', function ($req, $res, $service) {
    $service->pageTitle = 'ATMOS @ EMAC';
    $service->isMiniHeader = true;
    $service->isHiddenSidebar = true;
    $service->isForm = true;    // This includes neccessary scripts
    $service->render('pages/atmos-calculation.php');
});
````

#### Create the page:
Here's the basic structure. Refer to the Atmos example for the full code. We'll go through some form elements soon.

###### pages/atmos-calculation.php
````html
<!-- Calculation Form -->
<form enctype="multipart/form-data" class="form-horizontal" action="atmos/run" method="post" id="calculation-form">
    <!-- Add all the inputs here... -->
    <!-- At the end of the form... -->
    <div class="form-group">
        <div class="col-md-offset-3 col-md-9">
            <input type="hidden" name="calc_date" id="calc_date" value="" />
            <input type="hidden" name="tracking_id" id="tracking_id" value="0" />
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>

<!-- Result list -->
<div id="calculation-list" class="clearfix hidden"></div>

<!-- Results -->
<div id="calculation-result" class="clearfix hidden"></div>
````

#### Validate the form:
Set the rules! It's all about creating some simple json. Validators are executed in order.
<br/>
We're using an old open-source version of the [Form Validation](http://formvalidation.io) plugin called [Bootstrap Validator](https://github.com/nghuuphuoc/bootstrapvalidator).
<br/>
Here's an example configuration. Many more validators are available.
<br/>
You can even code a custom validator. See Pandexo form for an example, such as checking files for valid columns of numbers.

###### assets/js/atmos/atmos-calculation.js
````js
var validationConfigFields = {
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

/* Validate the form: */
$('#calculation-form').bootstrapValidator({
    verbose: false, /* displays max one error per element at a time */
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: validationConfigFields
}).on("success.form.bv", function (e) {
    e.preventDefault();                         // Prevent form submission
    var $form = $(e.target);                    // The form instance
    var bv = $form.data("bootstrapValidator");  // The validator instance
    if (bv) {
        if (bv.getSubmitButton()) {
            bv.disableSubmitButtons(false);     // Enable the submit button
        }
    }
    newCalculation($form);                      // Submit the form via ajax  <--- Calls our function which we'll define next
    return false;
});
````

#### Running Calculations:

The basic idea is as follows:<br/>
1. Submission is handled by the `newCalculation` function, which:<br/> Creates a new list item via `createListItem` function.<br/>Submits the form using `POST` to `atmos/run`.<br/>
1. Javascript identifies and keeps track of each calculation with a number, stored in the `trackingId` cookie.<br/>Call `getCurrentTrackingID()` for the latest calculation number.<br/>Call `setNextTrackingID()` to set and return a new id for the pending calculation.
1. The user is tracked via HTML Cookies or IP Address:<br/>Javascript requests a user identifier by setting the `needcookie` cookie.<br/>PHP sets the `userId` cookie with a unique generated id.<br/>If neither are set (cookies are disabled), then the user's IP address is used instead.<br/>This identifier can be accessed in PHP by calling `$userID = $service->getUserId->__invoke($res);` from within a route.
1. PHP processes the form:<br/>Validates the form to prevent hacks.<br/>Executes the given Python script in the background, and passes it the form data as JSON.<br/>Responds to Javascript with JSON (status 'running'), as discussed soon.
1. Javascript periodically pings PHP (at `atmos/check/<tracking_id>`), listening for the creation of a response file.
1. Python outputs a response file with calculation results.
1. Javascript marks the calculation as complete, and displays the results when asked.

#### Response Data:

All server responses are received by the Javascript `handleCalculation(response)` function.<br/>
Response must have any of the following formats:<br/>

###### Always sent by `/run` and, if calculation not complete, by `/check/<tracking_id>`:
````json
{
    status: "running",
    input: {
      tracking_id: "<user_id>_<tracking_id>"
    }
}
````

###### Sent by `/run` for validation error, and outputted by Python code for calculation errors:
````json
{
    status: "error",
    message: "Replace with some readable error message",
    input: {
      tracking_id: "<user_id>_<tracking_id>"
    }
}
````

###### Outputted by Python code, if successful, to `/python/outputs/<calculation_id>_response.json`:
````json
{
    status: "success",
    vmr_file: "python/outputs/<calculation_id>_profile2.pt",
    tp_file: "python/outputs/<calculation_id>_profile2.pt",
    input: "<all_form_data_object>"
}
````

#### Python code and output file generation:
Here's what is needed for php to detect outputted file.<br/>
Remember to properly set folder permissions for the `/python/outputs` directory to allow Python to write files.

###### atmos-calculation.py
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
Customize the `displayCalculation` Javascript function to display the plots and data according to your specifications.

#### Discover something amazing with calculation results...