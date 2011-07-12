<?php
/**
 * Initializes the application environment.
 * This file enables library autoloading, sets up error handling, and defines the PATH constants.
 * 
 * Modify as needed.
 */
 
 
ini_set('display_errors', 1); 
error_reporting(E_ALL | E_NOTICE | E_STRICT);
ini_set('html_errors', 0);

/**
 * Handler that converts errors into exceptions.
 * There is an issue when some code uses the '@' error suppressor. It doesn't actually suppress the error.
 * Rather, it sets the error level to a different level just for that expression.
 * this version of the handler doesn't take into account that problem. The bright side is that error supression is a mistake to begin with.
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});


/**
 * Path Constants
 * Convention: Paths have a trailing slash. Use only forward slashes, even on windows.
 * 
 * OAK_PATH: The central Oak directory.
 * APP_PATH: The base path of the current application.
 * APP_PUB_PATH: The public directory of the current application. This is where the HTTP server should point.
 * APP_RES_PATH: The resources directory in the base path of the current application.
 */
 
define('OAK_PATH', dirname(__FILE__) . '/');
define('APP_PUB_PATH', realpath('.') . '/');
define('APP_PATH', realpath(APP_PUB_PATH . '/..') . '/');
define('APP_RES_PATH', realpath(APP_PATH . '/resources') . '/');

/**
 * Library autoloading
 *
 * The ClassLoader uses the path format: <classpath>.php.
 * In other words case sensitivity of the file system will make a difference here.
 * The convention in the Oak library is to use lowercase namespaces and camelcase classes.
 */
include OAK_PATH . 'oak/ClassLoader.php';

$classLoader = new \oak\ClassLoader(OAK_PATH);

// Registering the autoloader can be done with the following call:
//spl_autoload_register(array($classLoader, 'loadClass'));
// Or, as a shortcut, the loader can do this by itself:
$classLoader->register(); 

// End of init.php
// At this point the app.php file which included this file will resume, where app-specific settings will be applied.