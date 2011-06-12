<?php
/**
 * Initializes the application environment.
 * This file enables library autoloading, sets up error handling, and defines the PATH constants.
 * The PATH constants are particularly useful for using the other features of Oak.
 * 
 * The purpose of this file is to have a ready configuration.
 * Nothing in this configuration is sacred. Rip it out and tear it up if you feel like it.
 * Don't be afraid to use only what you need, and modify the rest.
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
 * LIB_PATH: The central Oak library directory.
 * OAK_LIB_PATH: Identical to LIB_PATH
 * APP_PATH: The base path of the current application. It can store its files here.
 * APP_LIB_PATH: The library directory of the application. Used as an app-local library,
 *		it can be used as a second lookup location by ClassLoader.
 */
 
/*
	Path building reference:
		Path of presently included file:
			dirname(__FILE__) (varies depending on file where it's placed)
		Path of invoked file:
			'.' (the cwd is set to the location of the requested file, AFTER mod_rewrite)

	__FILE__ already returns an absolute path while '.' is relative. We can use realpath()
	to obtain an absolute path. This isn't really strictly necessary but in case you need
	to uniquely identify particular files or directories, it's a good idea.
*/
define('OAK_PATH', dirname(__FILE__) . '/');
define('LIB_PATH', OAK_PATH . 'lib/');
define('OAK_LIB_PATH', 'LIB_PATH'); // Alias
define('APP_PATH', realpath('../') . '/');
define('APP_LIB_PATH', realpath(APP_PATH . '/lib') . '/');

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
include LIB_PATH . 'oak/ClassLoader.php';

$classLoader = new \oak\ClassLoader(array(LIB_PATH, APP_LIB_PATH));

// Alternative search order: let the app lib override the central lib
//$classLoader = new \oak\ClassLoader(array(APP_LIB_PATH, LIB_PATH));

$classLoader->register(); 
// It's not a brilliant idea to use methods that have side-effects.
// In this case the register() method has the side effect of registering itself in the autoloader.
// It's a tradeoff between prettiness and explicitness.
// The following is the equivalent call:
//spl_autoload_register(array($classLoader, 'loadClass'));

