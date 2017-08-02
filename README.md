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
    <link href="css/emac/site.css" rel="stylesheet">
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
<!-- END NASA SUB-SITE INFORMATION -->
...
````

##### Sub Navigation
To remove, delete the reference to the _partial_ in `pages/partial/sub-header.php`
<br/>
To update the data, modify `pages/models/sub-navigation.json` file appropriately.