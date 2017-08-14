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