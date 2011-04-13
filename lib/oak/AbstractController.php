<?php

namespace oak;
/**
 * Base controller class.
 * Subclasses can implement the init method which simply gets called before the handler method.
 */
abstract class AbstractController {

	protected $request;

	public function __construct($request) {
		$this->request = $request;
		$this->init();
	}
	
	public function init() {
		// for overloading
	}
	
	
	// This is a pretty sad function. Find a solution.
    public function pageNotFound() {
        $page = "<!doctype html><html><body><h1>Page Not Found</h1><p>The URI does not exists. (404)</p></body></html>";
		return array($page, 404);
    }

}