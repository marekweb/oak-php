<?php

namespace oak;
/**
 * Handler invoker. Responsible for invoking a callback by 
 * instantiating a handler and calling the requested method.
 *
 * The invoker interface has one method:
 *   invoke($handlerCallback, $handlerConstructorArgument);
 *
 * A callback takes the form of an array with two members: array($className, $methodName).
 * The invoke method instantiates the given class (with the given constructor argument)
 * and calls the given method.
 */
class Invoker {

	public function __construct() {
	}

	public function invoke($handlerCallback, $handlerConstructorArgument) {

		list($handlerClass, $handlerMethod) = $handlerCallback;

		// This check is done here rather than with an is_callable() using the instance, because it's better to be able
		// to abort before the instance is created.
		if (!method_exists($handlerClass, $handlerMethod)) throw new Exception("Handler class method not found: '$handlerClass::$handlerMethod'.");

		$handlerInstance = new $handlerClass($handlerConstructorArgument);

		$returnValue = call_user_func(array($handlerInstance, $handlerMethod));

		return $returnValue;
	}

}