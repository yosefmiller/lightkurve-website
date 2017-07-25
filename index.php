<?php
/** Welcome to the NASA website. This is the main configuration
 * script which handles all routing for the website.
 * To create a new page, first create a file under the 'pages'
 * folder ending with '.php'. Next, create a new route below,
 * using the current routes as examples. Finally, if necessary, add
 * the page to the sidebar by modifying 'pages/partials/sidebar.php'.
 *
 * @author Yosef Miller
 * @email yosefmiller613@gmail.com
 * @date July 25, 2017
 * @docs https://github.com/klein/klein.php
 **/

/* Configure uri for subdirectory: */
$base = dirname($_SERVER['PHP_SELF']);
$orig_uri = $_SERVER['REQUEST_URI'];
$_SERVER['REQUEST_URI'] = substr($orig_uri, strlen($base));

/* Initialize Klein router: */
$dir = dirname(__FILE__);
require "$dir/api/autoloader.php";
$klein = new \Klein\Klein();

/* Configure main layout (for all pages): */
$klein->respond(function ($req, $res, $service, $app) {
    $service->layout('pages/partials/master-layout.php');
});

/* Main Routing: */
$klein->respond('/', function ($req, $res, $service) {
    $res->redirect('home/main')->send();
});
$klein->respond('/home/main', function ($req, $res, $service) {
    // $service->settings = json_decode(file_get_contents("pages/models/settings.json"), true);
    $service->pageTitle = 'Home Page - Solar System Exploration Division - 690';
    // $service->cssFile = 'about';
    $service->render('pages/home-main.php');
});

/* Handle errors (if no route is found): */
$klein->onHttpError(function ($code, $router, $matched, $method_matched, $http_exception) {
    switch ($code) {
        case 404:
            //$router->response()->header("HTTP/1.0 404 Not Found");
            $router->response()->header('X-PHP-Response-Code: 404', true, 404);
            $router->response()->header(':', true, 404);
            $service = $router->service();
            $service->pageTitle = "404 File Not Found";
            $service->render('pages/not-found-404.php');
            break;
        default:
            $service = $router->service();
            $service->code = $code;
            $service->pageTitle = "$code Server Error";
            $service->render('pages/server-error.php');
        // $router->response()->body('Oh no, a bad error happened that caused a '. $code);
    }
});

/* Execute all changes: */
$klein->dispatch();
?>