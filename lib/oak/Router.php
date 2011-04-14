<?php

namespace oak;

/**
 * Router for mapping request paths to handler callbacks.
 */
class Router {

	private $patterns;

    public function __construct(array $patterns) {
		$this->setPatterns($patterns);
    }
	
	public function getPatterns() {
		return $this->$patterns;
	}
	
	public function setPatterns(array $patterns) {
		$this->patterns = $patterns;
	}

    public function resolve($requestMethod, $requestPath) {
	
		$requestPathSegments = $this->getRequestPathSegments($requestPath);
		// Fail case: no request method routes defined
		if (array_key_exists($requestMethod, $this->patterns)) {	
			foreach ($this->patterns[$requestMethod] as $pattern => $callback) {
				
				$pathParams = $this->match($pattern, $requestPathSegments);
				if ($pathParams) {
					return array($callback, $pathParams);
				}
					// If it isn't a match, then no problem.
					// It's only a problem if there are no matches at all.
				continue;
			}
		}
		// Nothing
		
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
        if (count($patternSegments) != count($requestPathSegments)) return FALSE;

        foreach ($patternSegments as $index => $segment) {
            if (!empty($segment) and $segment[0] == ':') {
                $paramName = substr($segment, 1);
                $params[$paramName] = $requestPathSegments[$index];
            } elseif ($segment != '*') {
                if ($segment != $requestPathSegments[$index]) return FALSE;
            }
        }
        return $params;
    }
}