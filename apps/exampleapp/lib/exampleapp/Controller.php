<?php

/*
 * The namespace structure must match the directory structure. In this case, this class resides in lib/exampleapp/Controller.php
 */
namespace exampleapp;

/*
 * Inheriting from AbstractController takes care of the constructor.
 * All the constructor does is assign the request object to $this->request
 * and call the init method.
 */
class Controller extends \oak\AbstractController {

	public function init() {
	
		/*
		 * Here you can set up the resources you need, load config files, open database connections.
		 * Instantiate classes here as members for the handler methods to use.
		 * Create different controller classes for handlers that need different resources, if you want.
		 * There's no magical relationship between controller classes and paths; it's all
		 * up to how you configure the router.
		 */
		 
	}
	
	public function index() {
	
		/*
		 * This handler gets called for the index path, because that's how this app's router is configured.
		 * You can change the router settings in the app's index.php file.
		 */
	
		return "<h1>Oak Example App</h2><h2>Index</h2><p>This example shows you a minimal app. You can go anywhere from here.</p><p><a href=\"/greetings/friend\">Greetings page.</a></p>";
	}

	public function greetings() {
	
		/*
		 * Whether it's a GET or a POST request, the parameter array is located at
		 * $this->request->params. It's a plain array; if you want more functionality
		 * such as default values or validation, you can bring in a wrapper class.
		 */
		
		$name = htmlentities($this->request->params['name']);
		
		
		
		return "<h1>Oak Example App</h1><h2>Greetings</h2><p>Hello, {$name}.</p><p>The router path for this handler is 'greetings/:name'. Try changing the name in the path.</p>";
	
	}
	
	public function greetingsblank() {
		/*
		 * We added an additional handler for the 'greeting/' path without any parameter following it.
		 */
		
		$this->request->params['name'] = 'friend';
		return "<h1>Oak Example App</h1><h2>Greetings</h2><p>Hello world!</p>";
	}
	
	public function error() {
		return array("<h1>Oak Example App</h1><h2>Not found: " . $this->request->path ."</h2><p>You can route errors to a controller like any other request.", 404);
	}
	

}