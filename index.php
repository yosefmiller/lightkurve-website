<?php
/**
 * @author Yosef Miller
 * @email yosefmiller613@gmail.com
 * @date July 24, 2018
 * @api-docs https://github.com/klein/klein.php
 * @api-licence Klein router is under the MIT Licence
 **/

/* Initialize Klein router (from Composer): */
require "../vendor/autoload.php";
$klein = new \Klein\Klein();

/* Configure Routing: */
include "routes/common.php";
include "routes/app-lightkurve.php";
include "routes/errors.php";

/* Execute all changes: */
$klein->dispatch();
?>