<?php

/* Routes used for forms */
$klein->respond('POST', '/check/[i:id]', function ($req, $res, $service) {
    /* Get user id */
    $userID = $service->getUserId->__invoke($res);
    $calculationID = $userID . "_" . $req->param("id");

    /* Check if response file exists */
    $responseFileLink = "outputs/" . $calculationID . "_response.json";
    exec("pgrep -f ".$calculationID, $running);
    if (file_exists($responseFileLink)) {
        $res->json(json_decode(file_get_contents($responseFileLink)));
    }
    else if (count($running) > 1) {
        $res->json(["status" => "running", "input" => ["tracking_id" => $calculationID]]);
    }
    else {
        $res->json(["status" => "error", "input" => ["tracking_id" => $calculationID]]);
    }
});

$klein->respond('GET', '/logs/[i:id]', function ($req, $res, $service) {
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

$klein->respond('POST', '/clear/[i:id]?', function ($req, $res, $service) {
    /* Get user id */
    $userID = $service->getUserId->__invoke($res);
    if ($req->param("id")) $userID = $userID . "_" . $req->param("id");

    /* Delete files */
    foreach (glob("outputs/".$userID."*") as $filename) {
        unlink($filename);
    }

    /* Terminate running processes */
    exec("pkill -f ".$userID);

    /* Respond */
    echo "success"; die();
});