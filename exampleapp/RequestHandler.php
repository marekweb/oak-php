<?php

/*
 * The namespace structure must match the directory structure. In this case, this class resides in lib/exampleapp/Controller.php
 */
namespace exampleapp;

/*
 * The RequestHandler is a class we've created to contain handler methods -- the class doesn't have any
 * responsiblity beyond that of just containing these methods.
 */
class RequestHandler {
	
	public function index($context) {
	
		/*
		 * This handler gets called for the index path, because that's how this app's router is configured.
		 * You can change the router settings in the app's app.php file.
		 */
	
		return "<h1>Oak Example App</h2><h2>Index</h2><p>This example shows you a minimal app. You can go anywhere from here.</p><p><a href=\"/greetings/friend\">Greetings page.</a></p>";
	}

	public function greetings($context) {
	
		/*
		 * Whether it's a GET or a POST request, the parameter array is located at
		 * $context->params. It's a plain array; if you want more functionality
		 * such as default values or validation, you can use a wrapper class.
		 */
		
		$name = htmlentities($context->params['name']);
		
		return "<h1>Oak Example App</h1><h2>Greetings</h2><p>Hello, {$name}.</p><p>The router path for this handler is <code>greetings/:name</code>. Try changing the name in the path.</p>";
	
	}
	
	public function greetingsblank($context) {
	
		/*
		 * We added an additional handler for the 'greeting/' path without any parameter following it.
		 */
		
		return "<h1>Oak Example App</h1><h2>Greetings</h2><p>Hello world!</p>";
	}
	
	public function throwexception($context) {
		throw new \oak\Exception("WHat!");
	}
	public function pageNotFound($context) {
		return array("<h1>Oak Example App</h1><h2>Not found: " . $context->path ."</h2><p>You can route errors to a controller like any other request.", 404);
	}
	public function error($context) {
		return "If you are reading this, then something is very, very wrong.";

	}
	

}
