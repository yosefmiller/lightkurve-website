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
    $form_data = [
        "tracking_id"          => $tracking_id,
        "calc_date"            => getFormData($req, 'calc_date'),
        "calc_name"            => getFormData($req, "calc_name"),
        "data_archive"         => getFormData($req, 'data_archive'),
        "target"               => getFormData($req, 'target'),
        "mission"              => getFormData($req, 'mission',    true),
        "quarter"              => getFormData($req, 'quarter',    true),
        "campaign"             => getFormData($req, 'campaign',   true),
        "sector"               => getFormData($req, 'sector',     true),
        "search_radius"        => getFormData($req, 'search_radius'),
        "cadence"              => getFormData($req, 'cadence'),
        "month"                => getFormData($req, 'month',      true),
        "limit_targets"        => getFormData($req, 'limit_targets'),
        "is_search_only"       => getFormData($req, 'is_search_only'),
        "quality_bitmask"      => getFormData($req, 'quality_bitmask'),
        "cutout_size"          => getFormData($req, 'cutout_size'),
        
        "flux_type"            => getFormData($req, 'flux_type'),
        "photometry_type"      => getFormData($req, 'photometry_type'),
        "is_custom_aperture"   => getFormData($req, 'is_custom_aperture'),
        "aperture_type"        => getFormData($req, 'aperture_type'),
        "aperture_percent"     => getFormData($req, 'aperture_percent'),
        "aperture_rows"        => getFormData($req, 'aperture_rows'),
        "aperture_columns"     => getFormData($req, 'aperture_columns'),
        "aperture_custom"      => getFormData($req, 'aperture_custom'),
        "is_remove_nans"       => getFormData($req, 'is_remove_nans'),
        "is_remove_outliers"   => getFormData($req, 'is_remove_outliers'),
        "outlier_sigma"        => getFormData($req, 'outlier_sigma'),
        "is_fill_gaps"         => getFormData($req, 'is_fill_gaps'),
        "is_sff_correction"    => getFormData($req, 'is_sff_correction'),
        "windows"              => getFormData($req, 'windows'),
        "is_flatten"           => getFormData($req, 'is_flatten'),
        "flatten_window"       => getFormData($req, 'flatten_window'),
        "flatten_polyorder"    => getFormData($req, 'flatten_polyorder'),
        "flatten_tolerance"    => getFormData($req, 'flatten_tolerance'),
        "flatten_iterations"   => getFormData($req, 'flatten_iterations'),
        "flatten_sigma"        => getFormData($req, 'flatten_sigma'),
        "is_stitch"            => getFormData($req, 'is_stitch'),
        "is_fold"              => getFormData($req, 'is_fold'),
        "fold_period"          => getFormData($req, 'fold_period'),
        "fold_phase"           => getFormData($req, 'fold_phase'),
        "is_bin"               => getFormData($req, 'is_bin'),
        "bin_size"             => getFormData($req, 'bin_size'),
        "bin_count"            => getFormData($req, 'bin_count'),
        "is_normalize"         => getFormData($req, 'is_normalize'),
        "normalize_unit"       => getFormData($req, 'normalize_unit'),
        "is_cdpp"              => getFormData($req, 'is_cdpp'),
        "cdpp_duration"        => getFormData($req, 'cdpp_duration'),
        "cdpp_window"          => getFormData($req, 'cdpp_window'),
        "cdpp_polyorder"       => getFormData($req, 'cdpp_polyorder'),
        "cdpp_sigma"           => getFormData($req, 'cdpp_sigma'),
        "is_periodogram"       => getFormData($req, 'is_periodogram'),
        "p_method"             => getFormData($req, 'p_method'),
        "p_ls_normalization"   => getFormData($req, 'p_ls_normalization'),
        "p_ls_method"          => getFormData($req, 'p_ls_method'),
        "p_ls_oversample"      => getFormData($req, 'p_ls_oversample'),
        "p_ls_nterms"          => getFormData($req, 'p_ls_nterms'),
        "p_ls_nyquist"         => getFormData($req, 'p_ls_nyquist'),
        "p_ls_freq_period"     => getFormData($req, 'p_ls_freq_period'),
        "p_ls_frequencies"     => getFormData($req, 'p_ls_frequencies'),
        "p_ls_frequencies_unit"=> getFormData($req, 'p_ls_frequencies_unit'),
        "p_ls_frequencies_min" => getFormData($req, 'p_ls_frequencies_min'),
        "p_ls_frequencies_max" => getFormData($req, 'p_ls_frequencies_max'),
        "is_river_plot"        => getFormData($req, 'is_river_plot'),
        "river_plot_period"    => getFormData($req, 'river_plot_period'),
        "river_plot_time"      => getFormData($req, 'river_plot_time'),
        "river_plot_points"    => getFormData($req, 'river_plot_points'),
        "river_plot_phase_min" => getFormData($req, 'river_plot_phase_min'),
        "river_plot_phase_max" => getFormData($req, 'river_plot_phase_max'),
        "river_plot_method"    => getFormData($req, 'river_plot_method'),
        "is_view_metadata"     => getFormData($req, 'is_view_metadata')
    ];

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