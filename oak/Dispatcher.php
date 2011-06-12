<?php

namespace oak;

class Dispatcher {
		/* interface Router { resolve($requestMethod, $requestPath); } */
		private $router;
		private $invoker;

    public function __construct($router, $invoker) {
        $this->router = $router;
		$this->invoker = $invoker;
		    }

    public function dispatch($requestPath, $requestMethod = 'GET', $requestParams = array()) {
		
		$route = $this->router->resolve($requestMethod, $requestPath);
		if (!$route) {
			// This happens when the router doesn't have an error page to route to. Now that's harsh.
			$this->response('<pre>404 Not Found. Harsh error.</pre>', 404);
		}
		list($callback, $pathParams) = $route;
		
		
        $params = array_merge($pathParams, $requestParams);

        $request = new Request($requestMethod, $params, $requestPath, $this);

		// Invocation of the controller: this is where the application handles the request.
		$response = $this->invoker->invoke($callback, $request);
		$defaults = array('', 200, array());
		$response = (array) $response + $defaults;
		list($body, $status, $headers) = $response;
		$this->response($body, $status, $headers);
		
	}
	
	
	/**
	 * Sends a HTTP response with the given body, status and headers, and
	 * terminates the request. The status is an integer representing the HTTP
	 * response code, and the headers are given as a name-value mapping or
	 * a list of array (name, value) pairs.
	 * 
	 * @param string $body
	 * @param int $status
	 * @param array $headers
	 */
	public function response($body='', $status=200, array $headers=array()) {
	
		// Emit the status (if other than the default of 200)
		if ($status != 200) {
			header(' ', true, (int) $status);
			
			if ($status == 404 || $status == 500) {
				$body = str_pad($body, 512); // Some browsers discard the page
				// if it's under 512 bytes, instead displaying their own error
				// page on some errors (404 for instance).
			}
		}
		
		// Emit all the headers
		foreach ($headers as $key => $headerItem) {
			// Header items can be 2-tuples containing (name, value) which are emitted as "name: value"
			// Or just strings that are emitted as-is.
			// Or they could be a key-value pair
			if (is_int($key)) {
				// Indexed entry: either a 2-tuple or a string
				if (is_array($header) && count($headerItem == 2)) {
					$headerString = implode(': ', $header);
				} elseif (is_string($headerItem)) {
					$headerString = $headerItem;
				} else {
					throw new Exception("Invalid header value.");
				}
			} else	{
				$headerString = $key . ': ' . $headerItem;
			}
			
			
			header($headerString, false); // Second argument false makes
			// header() emit headers even if the header name is a duplicate of a
			// previously sent header. Setting it to true will overwrite.
		}
		
		// This is the only print statement; the entire body is emitted at once.
		print $body;
		
		// Flush the output buffer.
		flush(); // This might not really make any difference, since we exit.
		
		// Nothing afterwards will execute, this may be an unexpected behavior
		// if you put something after the dispatch call in the bootstrap script.
		// The purpose is to allow an app to override an otherwise response.
		// So the request has to be completed
		exit;
		// Note that shutdown functions will still run, followed by 
		// object destructors
    }

	
	/**
	 * Dispatches the current server request, using environment values.
	 * Calls the `dispatch` method with the path, request method and parameters
	 * obtained respectively from $_SERVER['REQUEST_URI'],
	 * $_SERVER['REQUEST_METHOD'], and the request variable array ($_GET or
	 * $_POST).
	 * @param void
	 * @return void
	 */
    public function dispatchFromEnvironment() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $requestParams = $_GET;
        } else {
            $requestParams = $_POST;
        }

        $requestPath = $_SERVER['REQUEST_URI'];
        $this->dispatch($requestPath, $requestMethod, $requestParams);
    }
}