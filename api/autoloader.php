<?php
/* According to PSR-0 from GitHub */
function autoload($className)
{
	/* Initialize variables: */
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
	
	/* Parse folder names: */
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);		// folder
        $className = substr($className, $lastNsPos + 1);	// class
		//if ($namespace == "Klein") { $namespace = "\klein-router\src\Klein"; }	// custom
        $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;	// parse folders
    }
	
	/* Prepend custom folders: */
	if ( begins_with($fileName, "Klein") ) { $fileName = "/klein-router/src/$fileName"; }
	
	/* Append the class name: */
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	
	/* Load the file: */
	$dir = dirname(__FILE__);
	require_once($dir . $fileName);
}

/* Helper function: */
function begins_with($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

/* Initialize autoload: */
spl_autoload_register('autoload');