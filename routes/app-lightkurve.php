<?php

$klein->respond('/?', function ($req, $res, $service) {
    $service->pageTitle = 'LightKurve @ EMAC';
    $service->isMiniHeader = true;
    $service->isHiddenSidebar = true;
    $service->isForm = true;
    $service->customJSFile = "js/lightkurve-calculation.js";
    $service->render('pages/lightkurve-calculation.php');
});

$klein->respond('POST', '/run', function ($req, $res, $service) {
    function errorJson ($message, $id) {
        return ["status" => "error", "type" => "validation", "message" => $message, "input" => ["tracking_id" => $id]];
    }

    /* Get user id */
    $userID = $service->getUserId->__invoke($res);

    /* Retrieve and sanitize input data */
    $tracking_id = $userID . "_" . filter_var($req->param('tracking_id'),    FILTER_SANITIZE_STRING);

    /* Store data */
    $form_data = [
        "tracking_id"        => $tracking_id,
        "calc_date"          => filter_var($req->param('calc_date'),         FILTER_SANITIZE_STRING),
        "calc_name"          => filter_var($req->param('calc_name'),         FILTER_SANITIZE_STRING),
        "data_archive"       => filter_var($req->param('data_archive'),      FILTER_SANITIZE_STRING),
        "flux_type"          => filter_var($req->param('flux_type'),         FILTER_SANITIZE_STRING),
        "target"             => filter_var($req->param('target'),            FILTER_SANITIZE_STRING),
        "limiting_factor"    => filter_var($req->param('limiting_factor'),   FILTER_SANITIZE_STRING),
        "quarter_campaign"   => filter_var($req->param('quarter_campaign'),  FILTER_SANITIZE_STRING),
        "quality_bitmask"    => filter_var($req->param('quality_bitmask'),   FILTER_SANITIZE_STRING),
        "cadence"            => filter_var($req->param('cadence'),           FILTER_SANITIZE_STRING),
    //  "month"              => filter_var(implode(",", $req->param('month')), FILTER_SANITIZE_STRING),
        "month"              => filter_var($req->param('month'),             FILTER_SANITIZE_STRING),
        "search_radius"      => filter_var($req->param('search_radius'),     FILTER_SANITIZE_STRING),
        "limit_targets"      => filter_var($req->param('limit_targets'),     FILTER_SANITIZE_STRING),
        "photometry_type"    => filter_var($req->param('photometry_type'),   FILTER_SANITIZE_STRING),
        "is_custom_aperture" => filter_var($req->param('is_custom_aperture'),FILTER_SANITIZE_STRING),
        "aperture_type"      => filter_var($req->param('aperture_type'),     FILTER_SANITIZE_STRING),
        "aperture_percent"   => filter_var($req->param('aperture_percent'),  FILTER_SANITIZE_STRING),
        "aperture_rows"      => filter_var($req->param('aperture_rows'),     FILTER_SANITIZE_STRING),
        "aperture_columns"   => filter_var($req->param('aperture_columns'),  FILTER_SANITIZE_STRING),
        "aperture_custom"    => filter_var($req->param('aperture_custom'),   FILTER_SANITIZE_STRING),
        "is_remove_nans"     => filter_var($req->param('is_remove_nans'),    FILTER_SANITIZE_STRING),
        "is_remove_outliers" => filter_var($req->param('is_remove_outliers'),FILTER_SANITIZE_STRING),
        "sigma"              => filter_var($req->param('sigma'),             FILTER_SANITIZE_STRING),
        "is_fill_gaps"       => filter_var($req->param('is_fill_gaps'),      FILTER_SANITIZE_STRING),
        "is_sff_correction"  => filter_var($req->param('is_sff_correction'), FILTER_SANITIZE_STRING),
        "windows"            => filter_var($req->param('windows'),           FILTER_SANITIZE_STRING),
        "is_periodogram"     => filter_var($req->param('is_periodogram'),    FILTER_SANITIZE_STRING),
        "frequencies"        => filter_var($req->param('frequencies'),       FILTER_SANITIZE_STRING),
        "is_flatten"         => filter_var($req->param('is_flatten'),        FILTER_SANITIZE_STRING),
        "window_length"      => filter_var($req->param('window_length'),     FILTER_SANITIZE_STRING),
        "is_fold"            => filter_var($req->param('is_fold'),           FILTER_SANITIZE_STRING),
        "period"             => filter_var($req->param('period'),            FILTER_SANITIZE_STRING),
        "phase"              => filter_var($req->param('phase'),             FILTER_SANITIZE_STRING),
        "is_bin"             => filter_var($req->param('is_bin'),            FILTER_SANITIZE_STRING),
        "bin_size"           => filter_var($req->param('bin_size'),          FILTER_SANITIZE_STRING),
        "bin_method"         => filter_var($req->param('bin_method'),        FILTER_SANITIZE_STRING),
        "is_normalize"       => filter_var($req->param('is_normalize'),      FILTER_SANITIZE_STRING),
        "is_view_metadata"   => filter_var($req->param('is_view_metadata'),  FILTER_SANITIZE_STRING)
    ];

    /* Execute Python script in background and output to log */
    $command = "python3 -u python/lightkurve-calculation.py";
    $raw_log_file = "outputs/" . $tracking_id . ".log";

    $escaped_form_data = escapeshellarg(json_encode($form_data));
    $escaped_log_file = escapeshellarg($raw_log_file);
    $pid = exec("$command $escaped_form_data > $escaped_log_file 2>&1 &");

    /* Respond */
    $res->json(["status" => "running", "input" => $form_data]);
});

/* Load common form routes */
include "form.php";