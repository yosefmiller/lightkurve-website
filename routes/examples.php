<?php

$klein->with('/example', function () use ($klein) {
    $klein->respond('/?', function ($req, $res, $service) {
        $service->pageTitle = 'Examples';
        $service->render("Welcome to the examples! Add any of the following to the web address: " .
            "/calculation, /home, /home/emac, /org/chart, /projects/alphabetical, /projects/featured");
    });
    $klein->respond('/calculation', function ($req, $res, $service) {
        $service->pageTitle = 'New Calculation | Pandexo';
        $service->isMiniHeader = true;
        $service->isHiddenSidebar = true;
        $service->isForm = true;
        $service->render('examples/pages/form-calculation.php');
    });
    $klein->respond('/home', function ($req, $res, $service) {
        $service->pageTitle = 'Code 690 Home | Example';
        $service->sidebarPath = 'examples/partials/sidebar.php';
        $service->render('examples/pages/home-main.php');
    });
    $klein->respond('/home/emac', function ($req, $res, $service) {
        $service->pageTitle = 'Exoplanet Modeling and Analysis Center - NASA/GSFC';
//        $service->isHiddenSubNav = true;
        $service->toolList = json_decode(file_get_contents("examples/models/emac-tool-list.json"), true);
        $service->render('examples/pages/home-emac.php');
    });
    $klein->respond('/org/chart', function ($req, $res, $service) {
        $service->pageTitle = 'Code 690 Org Chart | Example';
        $service->isHiddenSidebar = true;
        $service->render('examples/pages/people-org-chart.php');
    });
    $klein->respond('/projects/alphabetical', function ($req, $res, $service) {
        $service->pageTitle = 'Code 690 Projects Alphabetical | Example';
        $service->render('examples/pages/projects-alphabetical.php');
    });
    $klein->respond('/projects/featured', function ($req, $res, $service) {
        $service->pageTitle = 'Code 690 Projects Featured | Example';
        $service->render('examples/pages/projects-featured.php');
    });
});