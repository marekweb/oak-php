<?php
namespace oak;

define('NAMESPACE_SEPARATOR', '\\');

/**
 * Autoload implementation class following a simplified subset of the PSR-0 standard.
 */
class ClassLoader {
	private $basepath;
	public function __construct($searchPath) {
		$this->searchPath = $searchPath;
	}
	public function loadClass($className) {
				
		// Replace namespace separator with directory separator.
		$classPath = str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $className);
		
		$filePath = $this->searchPath . DIRECTORY_SEPARATOR . $classPath . '.php';
		if (file_exists($filePath)) {
			include($filePath);
		}
	}
	
	/**
	 * Registers this autoloader with the autoload system.
	 * This is a method with global side-effects.
	 */
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}
}