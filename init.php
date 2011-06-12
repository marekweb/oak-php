<?php
/**
 * Initializes the application environment.
 * This file enables library autoloading, sets up error handling, and defines the PATH constants.
 * 
 * Modify as needed.
 */
 
 
ini_set('display_errors', 1); 
error_reporting(E_ALL | E_NOTICE | E_STRICT);
ini_set('html_errors', true);

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
 * The ClassLoader resides in the library just like the other classes.
 * It takes an array of paths, which are used for lookups in that order.
 * ClassLoader looks in each path for desired classes, and stops the search once it is found.
 * If classes are present in multiple paths, then the earlier paths will take precedence.
 * The ClassLoader uses the path format; <classpath>.php where the classpath case is as-is.
 * In other words case sensitivity of the file system will make a difference here.
 * The convention in the Oak library has been to use lowercase namespaces and camelcase classes.
 */
include OAK_PATH . 'oak/ClassLoader.php';

$classLoader = new \oak\ClassLoader(array(OAK_PATH));


$classLoader->register(); 
// It's not a brilliant idea to use methods that have side-effects.
// In this case the register() method has the side effect of registering itself in the autoloader.
// It's a tradeoff between prettiness and explicitness.
// The following is the equivalent call:
//spl_autoload_register(array($classLoader, 'loadClass'));

