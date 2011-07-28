<?php
date_default_timezone_set ('UTC');
require '../../init.php';

$router = new \oak\Router(array(
	'GET' => array(
		'greetings/:name' => array('exampleapp\RequestHandler', 'greetings'),
		'greetings' => array('exampleapp\RequestHandler', 'greetingsblank'),
		'' => array('exampleapp\RequestHandler', 'index'),
	),

	'POST' => array(
		/* POST paths are separate from GET, but work the exact same way:
			'submitpath' => array('exampleapp\Controller', 'submithandler'),
		*/
	),
	// This is a special handler called when no routes match.
	'error' => array('exampleapp\RequestHandler', 'error'),
));

$invoker = new \oak\Invoker;

$dispatcher = new \oak\Dispatcher($router, $invoker, array('exampleweb\RequestHandler', 'pageNotFound'));

$dispatcher->dispatchFromEnvironment();
