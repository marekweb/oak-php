<?php

namespace oak;
/**
 * Request container.
 * Doesn't really do anything on its own.
 */
class Request {

	public $method;
	public $params;
	public $path;
	public $dispatcher;
        public $cookie;

	public function __construct($requestMethod, $params, $path, $dispatcher, $cookie) {
		$this->method = $requestMethod;
		$this->params = $params;
		$this->path = $path;
		$this->dispatcher = $dispatcher;
                $this->cookie = $cookie;
	}

}