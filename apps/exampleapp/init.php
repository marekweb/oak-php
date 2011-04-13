<?php
date_default_timezone_set ('UTC');
require '../../../init.php';

$router = new \oak\Router(array(
	'GET' => array(
		'archive' => array('marekweb\Controller', 'everything'),
		'greetings/:name' => array('exampleapp\Controller', 'greetings'),
		'greetings' => array('exampleapp\Controller', 'greetingsblank'),
		'' => array('exampleapp\Controller', 'index'),
	),

	'POST' => array(
		/* POST paths are separate from GET, but work the exact same way:
			'submitpath' => array('exampleapp\Controller', 'submithandler'),
		*/
	)
));

$invoker = new \oak\Invoker;

$dispatcher = new \oak\Dispatcher($router, $invoker, array('marekweb\Controller', 'pageNotFound'));

$dispatcher->dispatchFromEnvironment();
