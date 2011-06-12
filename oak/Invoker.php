<?php

namespace oak;

class CannotInvokeException extends \Exception {}
/**
 * Invoker responsible for instantiating a controller and calling the requested method.
 * The invoker has one interface method: invoke($callback, $controllerConstructorArgument);
 * It's up to the implementation how it wants to instantiate the object, and how it wants to call it.
 * callback is a 2-tuple: ($className, $methodName). 
 * TODO $controllerConstructorArgument could be an array instead: then we instantiate it with variable arguments.
 */
class Invoker {

	public function __construct() {
	}

	public function invoke($callback, $controllerConstructorArgument) {
		
		// Type check: this is one of the instances where we're more careful than usual
		// It's debatable whether this is worth doing, since it adds overhead that a correct app doesn't need.
		if (!is_array($callback) || count($callback) != 2) throw new Exception('Controller callback must be a 2-tuple (classname, method).');

		list($controllerClass, $controllerMethod) = $callback;
		
		// This check is done here rather than with an is_callable() using the instance, because it's better to be able
        // to abort before the instance is created.
	    if (!method_exists($controllerClass, $controllerMethod)) throw new Exception("Controller class method not found: '$controllerClass::$controllerMethod'.");

		if (!class_exists($controllerClass)) throw new Exception("Controller callback class not found: '$controllerClass'.");

		$controllerInstance = new $controllerClass($controllerConstructorArgument);
		
        $returnValue = call_user_func(array($controllerInstance, $controllerMethod));

		return $returnValue;
	}

}