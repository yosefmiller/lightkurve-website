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
    $service->getUserId = function ($response) {
        /* Configure cookies */
        if (isset($_COOKIE["userId"])) {
            // userid is already set - good to go!
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
        function errorJson ($message, $id) {
            return ["status" => "error", "type" => "validation", "message" => $message, "input" => ["tracking_id" => $id]];
        }

        /* Get user id */
        $userID = $service->getUserId->__invoke($res);

        /* Retrieve input values */
        $tracking_id = $userID . "_" . $req->param('tracking_id');
        $calc_name = $req->param('calc_name');
        $planet_template = $req->param('planet_template');
        $surface_gravity = $req->param('surface_gravity');
        $planet_radius = $req->param('planet_radius');

        /* Validate inputs */
        if (empty($calc_name)){ $res->json(errorJson("Please enter a calculation name.", $tracking_id)); }
        if (empty($planet_template)){ $res->json(errorJson("Please select a planet template.", $tracking_id)); }
        if (empty($surface_gravity)){ $res->json(errorJson("Please enter the surface gravity.", $tracking_id)); }
        if (empty($planet_radius)){ $res->json(errorJson("Please enter a planet radius.", $tracking_id)); }

        /* Validate input data */
        $calc_name = filter_var($calc_name, FILTER_SANITIZE_STRING);
        $planet_template = filter_var($planet_template, FILTER_SANITIZE_STRING);
        $surface_gravity = filter_var($surface_gravity, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $planet_radius = filter_var($planet_radius, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!$calc_name) { $res->json(errorJson("Please enter a valid calculation name.", $tracking_id)); }
        if (!$planet_template) { $res->json(errorJson("Please select a valid planet template.", $tracking_id)); }
        if (!$surface_gravity) { $res->json(errorJson("Please enter a valid surface gravity.", $tracking_id)); }
        if (!$planet_radius) { $res->json(errorJson("Please enter a valid planet radius.", $tracking_id)); }

        /* Store data */
        $form_data = [
            "tracking_id" => $tracking_id,
            "calc_name" => $calc_name,
            "planet_template" => $planet_template,
            "surface_gravity" => $surface_gravity,
            "planet_radius" => $planet_radius
        ];

        /* Execute Python script in background */
        $python_script = "python/atmos-calculation.py";
        passthru("python3 $python_script " . escapeshellarg(json_encode($form_data)) . " > /dev/null &");

        /* Respond */
        $res->json(["status" => "running", "input" => $form_data]);
    });
    $klein->respond('POST', '/check/[i:id]', function ($req, $res, $service) {
        /* Get user id */
        $userID = $service->getUserId->__invoke($res);
        $calculationID = $userID . "_" . $req->param("id");

        /* Check if response file exists */
        $responseFileLink = "python/outputs/" . $calculationID . "_response.json";
        if (file_exists($responseFileLink)) {
            $res->json(json_decode(file_get_contents($responseFileLink)));
        }
        $res->json(["status" => "running", "input" => ["tracking_id" => $calculationID]]);
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