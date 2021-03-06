<?php

namespace oak;

class Dispatcher {
	/* interface Router { resolve($requestMethod, $requestPath); } */
	private $router;
	private $invoker;

	public function __construct($router, $invoker, $contextClass = 'oak\Context') {
		$this->router = $router;
		$this->invoker = $invoker;
		$this->contextClass = $contextClass;
	}

	/**
	 * Dispatch a request path.
	 *
	 */
	public function dispatch($requestPath, $requestMethod = 'GET', $requestParams = array()) {

		$route = $this->router->resolve($requestMethod, $requestPath);
		if (!$route) {
			// This happens when the router doesn't have an error page to route to. 
			$this->response('<pre>404 Not Found.</pre>', 404);
		}
		list($callback, $pathParams) = $route;

		$params = array_merge($pathParams, $requestParams);

		$request = new $this->contextClass($requestMethod, $requestPath, $params, $this);

		// Invocation of the handler: this is where the application handles the request.
		try {
			$response = $this->invoker->invoke($callback, $request);
		//} catch (Exception $e) {
		//	$response = $this->fallbackExceptionHandler($request, $e);
		
		} catch (\Exception $e) {
			$callback = $this->router->getExceptionHandler();
			if (!is_null($callback)) {
				$response = $this->invoker->invoke($callback, $request);
			} else {
				$response = $this->fallbackExceptionHandler($request, $e);
			}
		}
		$defaults = array('', 200, array());
		$response = (array) $response + $defaults;
		list($body, $status, $headers) = $response;
		$this->response($body, $status, $headers);
	}

	public function fallbackExceptionHandler($request, $e) {
		return sprintf("<pre>An error occurred\n\n%s %s:%d\n%s\n%s</pre>", get_class($e), $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString());
	}

	/**
	 * Send a HTTP response with the given body, status and headers, and
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
				if (is_array($headerItem) && count($headerItem == 2)) {
					$headerString = implode(': ', $headerItem);
				} elseif (is_string($headerItem)) {
					$headerString = $headerItem;
				} else {
					throw new Exception("Invalid header value.");
				}
			} else	{
				$headerString = $key . ': ' . $headerItem;
			}

			header($headerString, false); // Second argument prevents header overwrite
		}

		// This is the only print statement; the entire body is emitted at once.
		print $body;
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
		$requestParams = $_GET;
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$requestParams = array_merge($requestParams, $_POST);
		}	

		$requestPath = $_SERVER['REQUEST_URI'];
		$this->dispatch($requestPath, $requestMethod, $requestParams);
	}
}
