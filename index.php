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
 * @date August 2, 2017
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
$klein->with('/atmos', function () use ($klein) {
    $klein->respond('/?', function ($req, $res, $service) {
        $service->pageTitle = 'ATMOS @ EMAC';
        $service->isMiniHeader = true;
        $service->isHiddenSidebar = true;
        $service->isForm = true;
        $service->render('pages/atmos-calculation.php');
    });
    $klein->respond('POST', '/run', function ($req, $res, $service) {
        /* Initialize response */
        $json = [];

        /* Retrieve input values */
        $tracking_id = $req->param('tracking_id');
        $calc_name = $req->param('calc_name');
        $planet_template = $req->param('planet_template');
        $surface_gravity = $req->param('surface_gravity');
        $planet_radius = $req->param('planet_radius');

        /* Validate inputs */
        if (empty($calc_name)){ $res->json(["status" => "error", "type" => "validation", "message" => "Please enter a calculation name."]); }
        if (empty($planet_template)){ $res->json(["status" => "error", "type" => "validation", "message" => "Please select a planet template."]); }
        if (empty($surface_gravity)){ $res->json(["status" => "error", "type" => "validation", "message" => "Please enter the surface gravity."]); }
        if (empty($planet_radius)){ $res->json(["status" => "error", "type" => "validation", "message" => "Please enter a planet radius."]); }

        /* Validate input data */
        $calc_name = filter_var($calc_name, FILTER_SANITIZE_STRING);
        $planet_template = filter_var($planet_template, FILTER_SANITIZE_STRING);
        $surface_gravity = filter_var($surface_gravity, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $planet_radius = filter_var($planet_radius, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!$calc_name) { $res->json(["status" => "error", "type" => "validation", "message" => "Please enter a valid calculation name."]); }
        if (!$planet_template) { $res->json(["status" => "error", "type" => "validation", "message" => "Please select a valid planet template."]); }
        if (!$surface_gravity) { $res->json(["status" => "error", "type" => "validation", "message" => "Please enter a valid surface gravity."]); }
        if (!$planet_radius) { $res->json(["status" => "error", "type" => "validation", "message" => "Please enter a valid planet radius."]); }

        /* Store data */
        $form_data = [
            "tracking_id" => $tracking_id,
            "calc_name" => $calc_name,
            "planet_template" => $planet_template,
            "surface_gravity" => $surface_gravity,
            "planet_radius" => $planet_radius
        ];

        /* Execute Python script */
        $python_script = "python/atmos-calculation.py";
        $result_text = shell_exec("python3 $python_script " . escapeshellarg(json_encode($form_data)));

        /* Parse result to json */
        $result_json = json_decode($result_text);

        sleep(1);
        /* Proceed */
        $res->json($result_json);
        //$res->redirect('running')->send();
    });
});
$klein->with('/example/calculation', function () use ($klein) {
    $klein->respond('/?', function ($req, $res, $service) {
        $service->pageTitle = 'New Calculation | Pandexo';
        $service->isMiniHeader = true;
        $service->isHiddenSidebar = true;
        $service->render('examples/pages/form-calculation.php');
    });
    $klein->respond('/running', function ($req, $res, $service) {
        $service->pageTitle = 'Running Calculation | Pandexo';
        $service->isMiniHeader = true;
        $service->isHiddenSidebar = true;
        $service->render("Running Calculation...");
    });
    $klein->respond('POST', '/run', function ($req, $res, $service) {
        // retrieve input values
        $name = $req->param('calcName');

        // validate inputs
        if (empty($name)){ echo "Please enter a calculation name."; die(); }
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        if (!$name) { echo "Please enter a valid calculation name."; die(); }

        // proceed
        $res->redirect('running')->send();
    });
});
$klein->respond('/docs', function ($req, $res, $service) {
    require_once('api/markup-parser/simplest-markdown-parser.php');
    $service->pageTitle = 'Documentation | EMAC Template';
    $service->isMiniHeader = true;
    $service->isHiddenSidebar = true;
    $docs_markdown = MD(file_get_contents("README.md"));
    $css_markdown = file_get_contents("api/markup-parser/github-markdown.css");
    // Changed yieldView function of Klein\ServiceProvider to enable rendering of a string
    $service->render("<style>$css_markdown</style><div class='markdown-body'>$docs_markdown</div>");
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