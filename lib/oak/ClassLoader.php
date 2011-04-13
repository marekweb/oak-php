<?php
namespace oak;

define('NAMESPACE_SEPARATOR', '\\');

/**
 * Autoload implementation class following a simplified subset of the PSR-0 standard.
 */
class ClassLoader {
	private $basepath;
	public function __construct($searchPaths) {
		$this->searchPaths = (array) $searchPaths;
	}
	public function loadClass($className) {
		
		// When class names appear in literal form in the source, the autoloader
		// is sent the fully qualified name with initial backslash. But when used
		// as a callback or to dynamically instantiate a class, the backslash can be
		// omitted and it's still treated as a fully qualified name. (todo test: is this strictly true?)
		$className = trim($className, NAMESPACE_SEPARATOR); 
		
		// Replace namespace separator with directory separator.
		$classPath = str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $className);
		
		// Search each location in order.
		foreach ($this->searchPaths as $basePath) {
			$filePath = $basePath . DIRECTORY_SEPARATOR . $classPath . '.php';
			if (file_exists($filePath)) {
				include($filePath);
				return; // If successful, we can get out of here.
			}
		}
		// The autoloader doesn't return anything; it just either includes the class or not.
	}
	
	/**
	 * Registers this autoloader with the autoload system.
	 * This is a method with global side-effects.
	 */
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}
}