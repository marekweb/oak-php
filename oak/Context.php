<?php

namespace oak;
/**
 * Context container.
 * Extend this class to provide resources or services to controllers.
 */
class Context {

	public $method;
	public $params;
	public $path;
	public $dispatcher;

	public function __construct($requestMethod, $path, $params, $dispatcher) {
		
		$this->dispatcher = $dispatcher;
		$this->method = $requestMethod;
		$this->params = $params;
		$this->path = $path;
		
		$this->init();
	}
	
	public function init() {
		// Override this with a subclass
	}

}
