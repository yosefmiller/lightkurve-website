<?php

require_once "helpers.php";

/* Routes used for forms */
$klein->respond('GET', '/list', function ($req, $res, $service) {
    /* Get user id */
    $userID = $service->getUserId->__invoke($res) . "_";
    
    /* Find files */
    $files      = glob("outputs/".$userID."*");
    $files_time = array_map('filectime', $files);
    array_multisort($files_time, SORT_NUMERIC, SORT_ASC, $files);
    
    /* Extract Tracking ID from filenames */
    foreach ($files as $filename) {
        $tracking_id = explode($userID, $filename);
        $tracking_id = array_pop($tracking_id);
        $tracking_id  = current(explode(".", $tracking_id, 2));
        $tracking_id  = current(explode("_", $tracking_id));
        $calc_list[] = $tracking_id;
    }
    
    /* Return list of unique Tracking IDs */
    $calc_list = array_unique($calc_list ?? []);
    foreach ($calc_list as $calc_id) {
        $calc_status_list[] = checkStatus($userID . $calc_id);
    }
    $res->json(["list" => $calc_status_list ?? []]);
});

$klein->respond('POST', '/check/[a:id]', function ($req, $res, $service) {
    /* Get user id */
    $userID = $service->getUserId->__invoke($res);
    $calculationID = $userID . "_" . $req->param("id");

    /* Check calculation status */
    $res->json(checkStatus($calculationID));
});

$klein->respond('GET', '/logs/[a:id]', function ($req, $res, $service) {
    /* Get user id */
    $userID = $service->getUserId->__invoke($res);
    $calculationID = $userID . "_" . $req->param("id");

    /* Check if log file exists */
    $responseFileLink = "outputs/" . $calculationID . ".log";
    $res->file($responseFileLink);
});

$klein->respond('GET', '/outputs/[:filename]', function ($req, $res, $service) {
    /* Check if response file exists */
    $responseFileLink = "outputs/" . $req->param("filename");
    $res->file($responseFileLink);
});

$klein->respond('POST', '/clear/[a:id]?', function ($req, $res, $service) {
    /* Get user id */
    $userID = $service->getUserId->__invoke($res);
    if ($req->param("id")) $userID = $userID . "_" . $req->param("id");
    
    /* Terminate running processes */
    exec("pkill -f ".escapeshellarg($userID));
    
    /* Delete files */
    foreach (glob("outputs/".$userID."*") as $filename) {
        unlink($filename);
    }

    /* Respond */
    echo "success"; die();
});

$klein->respond('GET', '/admin/status', function ($req, $res, $service) {
    /* Get list of running calculations and their info */
    $running_calculations = getRunningCalcInfo();
    
    /* Print status */
    echo "<pre>There are " . count($running_calculations) . " running calculations.";
    foreach ($running_calculations as $info) {
        echo "\nPID: ", $info[ "pid" ], ". CALC_ID: ", $info[ "calc_id" ], ". AGE: ", $info[ "age" ], " sec";
    }
    
    /* Get list of files in outputs directory */
    $files_all  = glob("outputs/*");
    $files_json = glob("outputs/*.json");
    $files_log  = glob("outputs/*.log");
    
    echo sprintf("\nThere are %s total output files (%s json, %s log).", count($files_all ?? []), count($files_json ?? []), count($files_log ?? []));
});

$klein->respond('GET', '/admin/cleanup', function ($req, $res, $service) {
    /* Cleanup old calculations */
    echo "<pre>";
    cleanupCalculations();
});

/** SHARED FUNCTIONS: **/
function checkStatus ($tracking_id) {
    /* Check if response file exists, otherwise check process status */
    $responseFileLink = "outputs/" . $tracking_id . "_response.json";
    exec("pgrep -f ".escapeshellarg($tracking_id), $running);
    
    /* Determine status */
    if (file_exists($responseFileLink)) {
        $json_response = json_decode(file_get_contents($responseFileLink));
    } else if (count($running) > 1) {
        $json_response = ["status" => "running", "input" => ["tracking_id" => $tracking_id]];
    } else {
        $json_response = ["status" => "error", "input" => ["tracking_id" => $tracking_id]];
    }
    return $json_response;
}