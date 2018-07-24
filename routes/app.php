<?php

$klein->respond('/?', function ($req, $res, $service) {
    $service->pageTitle = 'ATMOS @ EMAC';
    $service->isMiniHeader = true;
    $service->isHiddenSidebar = true;
    $service->isForm = true;
    $service->customJSFile = "js/atmos-calculation.js";
    $service->render('pages/atmos-calculation.php');
});

$klein->respond('POST', '/run', function ($req, $res, $service) {
    function errorJson ($message, $id) {
        return ["status" => "error", "type" => "validation", "message" => $message, "input" => ["tracking_id" => $id]];
    }

    /* Get user id */
    $userID = $service->getUserId->__invoke($res);

    /* Retrieve input values */
    $tracking_id     = $userID . "_" . $req->param('tracking_id');
    $calc_date       = $req->param('calc_date');
    $calc_name       = $req->param('calc_name');
    $planet_template = $req->param('planet_template');
    $surface_gravity = $req->param('surface_gravity');
    $planet_radius   = $req->param('planet_radius');

    /* Validate inputs */
    if (empty($calc_name))       { $res->json(errorJson("Please enter a calculation name.",  $tracking_id)); }
    if (empty($planet_template)) { $res->json(errorJson("Please select a planet template.",  $tracking_id)); }
    if (empty($surface_gravity)) { $res->json(errorJson("Please enter the surface gravity.", $tracking_id)); }
    if (empty($planet_radius))   { $res->json(errorJson("Please enter a planet radius.",     $tracking_id)); }

    /* Validate input data */
    $calc_name       = filter_var($calc_name, FILTER_SANITIZE_STRING);
    $planet_template = filter_var($planet_template, FILTER_SANITIZE_STRING);
    $surface_gravity = filter_var($surface_gravity, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $planet_radius   = filter_var($planet_radius, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    if (!$calc_name)       { $res->json(errorJson("Please enter a valid calculation name.", $tracking_id)); }
    if (!$planet_template) { $res->json(errorJson("Please select a valid planet template.", $tracking_id)); }
    if (!$surface_gravity) { $res->json(errorJson("Please enter a valid surface gravity.",  $tracking_id)); }
    if (!$planet_radius)   { $res->json(errorJson("Please enter a valid planet radius.",    $tracking_id)); }

    /* Store data */
    $form_data = [
        "tracking_id"     => $tracking_id,
        "calc_date"       => $calc_date,
        "calc_name"       => $calc_name,
        "planet_template" => $planet_template,
        "surface_gravity" => $surface_gravity,
        "planet_radius"   => $planet_radius
    ];

    /* Execute Python script in background */
    $command = "python3 python/atmos-calculation.py";
    $raw_log_file = "outputs/" . $tracking_id . ".log";
    $pid = exec($command . " " . escapeshellarg(json_encode($form_data)) . " > " . $raw_log_file . " 2> " .  $raw_log_file ." &");

    /* Respond */
    $res->json(["status" => "running", "input" => $form_data]);
});

/* Load common form routes */
include "form.php";