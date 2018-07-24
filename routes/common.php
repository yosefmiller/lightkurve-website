<?php

/* Configure main layout (for all pages): */
$klein->respond(function ($req, $res, $service, $app) {
    $service->layout('pages/partials/master-layout.php');
    $service->subNavigation = json_decode(file_get_contents("pages/models/sub-navigation.json"), true);
    $service->getUserId = function ($response) {
        /* Configure cookies */
        if (isset($_COOKIE["userId"]) && strlen($_COOKIE["userId"]) > 10) {
            // UserID is already set - good to go!
            $userId = $_COOKIE["userId"];
        } else if (isset($_COOKIE["needcookie"])) {
            // If cookies are enabled and userId is not set
            $response->cookie('needcookie', 0, time() - 3600);

            // Generate a new userId
            $userId = uniqid("user_");

            // And set the cookie
            $exp = time() + 60*60*24*365*10;  // 10 years
            $response->cookie('userId', $userId, $exp);
        } else {
            // If cookies are disabled, use IP address
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $IPv = $_SERVER['HTTP_CLIENT_IP'];
            } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $IPv = trim($ips[count($ips) - 1]);
            } else {
                $IPv = $_SERVER['REMOTE_ADDR'];
            }
            $IP = 'IP_' . $IPv;
            $userId = str_replace(array(":"," ",".","/"), array("","","",""), $IP);
        }
        return $userId;
    };
    $app->register('db', function() {
        $dbname = "emac";
        $dbuser = "emac";
        $dbpass = "emacrocks!";
        return new PDO("mysql:host=mysql;port=3306;charset=utf8;dbname=" . $dbname, $dbuser, $dbpass);
    });
});