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
    function getFormData ($req, $name, $isList = false) {
        if ($isList) {
            $list  = $req->param($name) ?? array();
            $value = implode(",", $list);
            return filter_var($value, FILTER_SANITIZE_STRING);
        } else {
            return filter_var($req->param($name), FILTER_SANITIZE_STRING);
        }
    }

    /* Get user id */
    $userID = $service->getUserId->__invoke($res);
    
    /* Generate calculation tracking id */
    $tracking_id = $userID . "_" . substr(uniqid(),-5 );
    $temp_id     = $req->param('tracking_id');

    /* Store data */
    $input_fields = [ "calc_date", "calc_name", "data_archive", "target", "search_radius", "cadence", "limit_targets", "is_search_only",
                      "quality_bitmask", "cutout_size", "flux_type", "photometry_type",
                          "is_custom_aperture", "aperture_type", "aperture_percent", "aperture_rows", "aperture_columns", "aperture_custom",
                          "is_remove_nans",
                          "is_remove_outliers", "outlier_sigma",
                          "is_fill_gaps",
                          "is_sff_correction", "sff_windows",
                          "is_flatten", "flatten_window", "flatten_polyorder", "flatten_tolerance", "flatten_iterations", "flatten_sigma",
                          "is_stitch",
                          "is_fold", "fold_period", "fold_phase",
                          "is_bin", "bin_size", "bin_count", "bin_method",
                          "is_normalize", "normalize_unit",
                      "is_periodogram", "p_method",
                          "p_ls_normalization", "p_ls_method", "p_ls_oversample", "p_ls_nterms", "p_ls_nyquist", "p_ls_freq_period", "p_ls_frequencies_unit", "p_ls_frequencies_min", "p_ls_frequencies_max",
                          "p_bls_duration", "p_bls_minimum_period", "p_bls_maximum_period", "p_bls_frequency_factor", "p_bls_time_unit",
                      "is_seismology",
                      "is_cdpp", "cdpp_duration", "cdpp_window", "cdpp_polyorder", "cdpp_sigma",
                      "is_river_plot", "river_plot_period", "river_plot_time", "river_plot_points", "river_plot_phase_min", "river_plot_phase_max", "river_plot_method",
                      "is_view_metadata" ];
    $input_lists = [ "mission", "quarter", "campaign", "sector", "month" ];
    
    // Add form data
    $form_data = [ "tracking_id" => $tracking_id ];
    foreach ($input_fields as $field) { $form_data[$field] = getFormData($req, $field); }
    foreach ($input_lists as $field)  { $form_data[$field] = getFormData($req, $field, true); }

    /* Execute Python script in background and output to log */
    $command = "python3 -u python/master.py";
    $raw_log_file = "outputs/" . $tracking_id . ".log";

    $escaped_form_data = escapeshellarg(json_encode($form_data));
    $escaped_log_file = escapeshellarg($raw_log_file);
    exec("$command $escaped_form_data > $escaped_log_file 2>&1 &");

    /* Respond */
    $res->json(["status" => "running", "temp_id" => $temp_id, "input" => $form_data]);
});

/* Load common form routes */
include "form.php";