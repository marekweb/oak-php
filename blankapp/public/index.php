<?php
date_default_timezone_set ('UTC');
require '../../init.php';

$router = new \oak\Router(array(
	'GET' => array(

	),

	'POST' => array(

	)
));

$invoker = new \oak\Invoker;

$dispatcher = new \oak\Dispatcher($router, $invoker);

$dispatcher->dispatchFromEnvironment();
