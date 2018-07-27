<?php

/* Handle errors (if no route is found): */
$klein->onHttpError(function ($code, $router, $matched, $method_matched, $http_exception) {
    switch ($code) {
        case 404:
            $router->response()->code("404");
            $router->response()->header('X-PHP-Response-Code: 404', true, 404);
            $router->response()->header(':', true, 404);
            $router->response()->sendHeaders(true, true);
            $service = $router->service();
            $service->pageTitle = "404 File Not Found";
            $service->isMiniHeader = true;
            $service->isHiddenSidebar = true;
            $service->render('pages/not-found-404.php');
            break;
        default:
            $router->response()->code($code);
            $router->response()->sendHeaders(true, true);
            $service = $router->service();
            $service->code = $code;
            $service->pageTitle = "$code Server Error";
            $service->isMiniHeader = true;
            $service->isHiddenSidebar = true;
            $service->render('pages/server-error.php');
    }
});