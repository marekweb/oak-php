Oak php
=======

_Oak_ is an unframework in PHP. It's aimed at small, lightweight, do-it-yourself apps. It avoids the heavy weight of bloated enterprise frameworks, and takes advantage of the strengths of PHP instead of over-engineering and obscuring its inner workings.

Principles:

 * Ultra-explicit configuration; no magic.
 * Ultra-thin layer over plain php; no wizardry.
 * Ultra-loose coupling; no convoluted architecture or dependecies.

	
Organization
---------

PHP organization is a solved problem: the PSR-0 standard. lasses and namespace structure is reflected in the file system. A class called `myapp\validators\DataValidator` is expected in `myapp/validators/DataValidator.php`. Autoloading is done with a simple class loader.

The oak directory is used as the root of the class layout, and oak's own classes are placed in the oak namespace. Multiple apps can coexist in the same directory structure by using separate namespaces.

Namespaces are used to separate apps from one another. An additional directory is used within the namespace for public files (where the web server document root should point) and another is used for private resources. These live alongside sub-namespaces but don't contain any PHP classes.

Decoupling
----------

Oak only contains a few classes at its core, and they're decoupled using simple dependency injection. Whenever a class needs another class' functionality, it accepts an instance object as a constructor paramter. There are no hardcoded dependencies. Follow this convention for the rest of your app and you're all set.

Keeping things minimal, the Oak classes don't use interface classes. Adding interfaces would make things more formal and strict, but it's not really worth it to create a whole set of interface classes for such a small framework. 

Explicit configuration
----------------------

A straightforward mod_rewrite directive routes all requests to the `index.php` in an app's public directory. This file is yours to edit and modify. This file includes the global `init.php` file located in the root directory. All object instantiations and configuration settings are done right there; nothing is hidden behind the scenes. Modify it as much as you like -- you can grab the default if you need to revert anything.

Simple control flow
-------------------

At the end of the app's init file, the dispatcher is called and it routes the request to the appropriate controller method. The Dispatcher class uses two servant classes to do this: the Router, which selects a controller based on the request path, and the Invoker, which instantiates the controller class and calls the handler method. These can easily be replaced with your own classes.

Getting started
---------------

Download Oak and see the included example app. It has explanations in the comments. There's also a blank app that contains the default minimal init files and the htaccess.

License and authors
-------------------

Copyright (c) 2011 Marek Z.

Released under the MIT License. See MIT-LICENSE.txt.

