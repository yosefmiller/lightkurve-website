<?php
/** Welcome to the EMAC template. This is the main configuration
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
 * @licence Klein router is under the MIT Licence
 **/

/* Configure uri for subdirectory: */
/* Might need to comment this out depending on configuration: */
$base = dirname($_SERVER['PHP_SELF']);
$orig_uri = $_SERVER['REQUEST_URI'];
$_SERVER['REQUEST_URI'] = substr($orig_uri, strlen($base));

/* Initialize Klein router: */
/* Klein can also be installed via Composer */
$dir = dirname(__FILE__);
require "$dir/api/autoloader.php";
$klein = new \Klein\Klein();

/* Configure main layout (for all pages): */
$klein->respond(function ($req, $res, $service, $app) {
    $service->layout('pages/partials/master-layout.php');
    $service->subNavigation = json_decode(file_get_contents("pages/models/sub-navigation.json"), true);
});

/* Main Routing: */
$klein->respond('/', function ($req, $res, $service) {
    $res->redirect('home')->send();
});
$klein->respond('/home', function ($req, $res, $service) {
    $service->pageTitle = 'Exoplanet Modeling and Analysis Center - NASA/GSFC';
    $service->render('pages/emac-home.php');
});
$klein->respond('/docs', function ($req, $res, $service) {
    require_once('api/markup-parser/simplest-markdown-parser.php');
    $docs_title = 'Documentation | EMAC Template';
    $docs_markdown = MD(file_get_contents("README.md"));
    $css_markdown = file_get_contents("api/markup-parser/github-markdown.css");
    $res->body("<title>$docs_title</title><style>$css_markdown</style><div class='markdown-body'>$docs_markdown</div>");
});

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
            $service->render('pages/not-found-404.php');
            break;
        default:
            $router->response()->code($code);
            $router->response()->sendHeaders(true, true);
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