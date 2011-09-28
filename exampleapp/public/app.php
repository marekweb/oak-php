<?php
date_default_timezone_set ('UTC');
require '../../init.php';

$router = new \oak\Router(array(
	'GET' => array(
		'greetings/:name' => array('exampleapp\RequestHandler', 'greetings'),
		'greetings' => array('exampleapp\RequestHandler', 'greetingsblank'),
		'err' => array('exampleapp\RequestHandler', 'throwexception'),
		'' => array('exampleapp\RequestHandler', 'index'),

	),

	'POST' => array(
		/* POST paths are separate from GET, but work the exact same way:
			'submitpath' => array('exampleapp\Controller', 'submithandler'),
		*/
	),



	// This is a special handler called when no routes match.
	'default' => array('exampleapp\RequestHandler', 'pageNotFound'),
	
	// This is a special handler called when an exception is thrown in the handler call.
	//	'exception' => array('exampleapp\RequestHandler', 'error'),
	
));

$invoker = new \oak\Invoker;

$dispatcher = new \oak\Dispatcher($router, $invoker);

$dispatcher->dispatchFromEnvironment();
