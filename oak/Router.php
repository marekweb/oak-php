<?php

namespace oak;

/**
 * Router for mapping request paths to handler callbacks.
 */
class Router {

	protected $patterns;

	public function __construct(array $patterns) {
		$this->patterns = $patterns;
	}

	public function resolve($requestMethod, $requestPath) {
		$requestPathSegments = $this->getRequestPathSegments($requestPath);
		if (array_key_exists($requestMethod, $this->patterns)) {
			foreach ($this->patterns[$requestMethod] as $pattern => $callback) {
				$pathParams = $this->match($pattern, $requestPathSegments);

				if (!is_null($pathParams)) {
					return array($callback, $pathParams);
				}
			}
		}

		if (array_key_exists('error', $this->patterns)) {
			return array($this->patterns['error'], array());
		}
		return FALSE;
	}


	public function getRequestPathSegments($requestPath) {
		list($requestPath) = explode('?', $requestPath, 2);
		$requestPath = trim($requestPath, '/');
		return explode('/', $requestPath);
	}

	public function getPatternSegments($pattern ) {
		$pattern = trim($pattern, '/');
		return explode('/', $pattern);
	}

	public function match($pattern, $requestPathSegments) {
	
		$patternSegments = $this->getPatternSegments($pattern);

		$params = array();
		if (count($patternSegments) != count($requestPathSegments)) return NULL;

		foreach ($patternSegments as $index => $segment) {
			if (!empty($segment) and $segment[0] == ':') {
				$paramName = substr($segment, 1);
				$params[$paramName] = $requestPathSegments[$index];
			} elseif ($segment != '*') {
				if ($segment != $requestPathSegments[$index]) return NULL;
			}
		}
		return $params;
	}
}