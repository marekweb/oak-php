Oak php
=======

_Oak_ is an _unframework_ in PHP. It's aimed at small, lightweight, do-it-yourself apps, completely avoiding the heavy weight of bloated frameworks. It builds up from the strengths of php, instead of over-engineering and using automagical smoke and mirrors.

What it implements:

 * Path routing.
 * Standardized class autoloading.

	
Principles:

 * Ultra-explicit configuration, no magic.
 * Ultra-thin layer over plain php, no wizardry.
 * Ultra-loose coupling, no convoluted architecture.

	
Organization
---------

Organization is a solved problem. Follow the PSR-0 standard, where classes and namespace structure is reflected in the file system. A class called `myapp\validators\DataValidator` is expected in `myapp/validators/DataValidator.php`. Autoloading is done with a simple class loader.

The autoloading mechanism is the only thing that isn't strictly explicit in Oak, but it's part of PHP and is backed by a standard. So it isn't some conjured up magical behavior.

Decoupling
----------

Oak only contains a few classes at its core, and they're decoupled using simple dependency injection. Whenever a class needs another class' functionality, it accepts an instance object as a paramter. There are no hardcoded dependencies. Follow this convention for the rest of your app and you're all set.

Keeping things minimal, the Oak classes don't use interface classes. Adding interfaces would make things more formal and strict, but it's debatable whether it's actually worth it. 

Explicit configuration
----------------------

A straightforward mod_rewrite directive routes all requests to a single php file. This file is yours to edit and modify. By default, `init.php` includes the global Oak init file, which sets up some useful path constants and the class autoloader. All object instantiations are done right there; nothing is hidden behind the scenes. Modify it as much as you like -- you can just grab the default one if you need to go back.

Simple control flow
-------------------

At the end of the init file, the dispatcher is called and it routes the request to the appropriate controller method. The Dispatcher class uses two servant classes to do this: the Router, which selects a controller based on the request path, and the Invoker, which instantiates the controller class and calls the handler method. These can easily be replaced with your own classes.

Getting started
---------------

Download Oak and see the included example app. It has explanations in the comments. There's also a blank app that contains the default init files and the htaccess.

Authors
-------

oak-php is &copy; 2011 @marekweb