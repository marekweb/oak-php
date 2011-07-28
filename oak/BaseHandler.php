<?php

namespace oak;
/**
 * Base handler class.
 * Subclasses can implement the init method which simply gets called before the handler method.
 */
abstract class BaseHandler {

	protected $request;

	public function __construct($request) {
		$this->request = $request;
		$this->init();
	}
	
	public function init() {
		// for overloading
	}
	
}