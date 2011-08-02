Oak PHP
=======

Oak is a foundation framework in PHP. It's aimed at small, lightweight apps, and provides a basic foundation which can be used for MVC or any other type of configuration.

Oak solves several problems by using accepted best practices in PHP 5.3:

 * Class and file structure. Classes are namespaced and stored in a namespace directory tree.
 * Routing URL requests. All requests are routed through a single PHP file.
 * Dispatching to handlers. Requests are dispatched to handlers (class and method name callbacks) based on matches in a routing table.
 
It does its job in only about 10kB of MIT-licensed source code.
 
Principles:

 * Explicit configuration; no magic.
 * Thin layer over plain php; no obscured inner workings.
 * Loose coupling; no convoluted architecture or dependecies.

	
Class and file structure
------------------------

The PSR-0 standard describes the preferred file structure for PHP applications. The directory tree mirrors namespaces, and files are named after classes. A class called `\foo\bar\MyClass` is expected to be found in `foo/bar/MyClass.php`. Oak's PSR-0 compliant autoloader makes class loading transparent.

Multiple apps can coexist in the same directory structure by using separate namespaces. This follows from the assumption that each app should have its own namespace. Oak's own classes are located in the `oak` namespace.

Decoupling
----------

Oak only contains a few classes at its core, and they're decoupled using simple dependency injection. Any class which depends on another will, by convention, accept an instance of the other class as a constructor paramter. As a result there are no hardcoded dependencies, and you can swap out any Oak class for your own.

Because of the namespaced structure, it's possible to drop in classes and add new functionality while keeping with the PSR-0 best practices.

Routing requests and configuration files
----------------------------------------

In an app's `public` directory, a `.htaccess` file routes all requests to the app's `app.php` file. This `app.php` file is yours to edit and modify. This file includes the `init.php` file located in the root Oak directory. All object instantiations and configuration settings are done right there, either in `app.php` or in `init.php`; nothing is hidden behind the scenes.

Public files
------------

By convention, an app's namespace directory also contains a `public` directory. This is where the necessary `.htaccess` and `app.php` files are found, and it's where your webserver points to as the document root.

The `app.php` file contains the configuration specific to the app. It loads the `init.php` file in the Oak root which contains the global Oak configuration.


Dispatching requests to handlers
--------------------------------

After the configuration section in the `app.php` file, the Dispatcher is called and it routes the request to the appropriate handler method. The Dispatcher class uses two servant classes to do this: the Router, which accepts a URL and returns a matched callback name (class and method), and the Invoker, which receives a callback name and instantiates the class, before calling the specified method.

By convention a class which contains handler methods is called a Controller, and it must accept a Request object as its sole constructor parameter. The Request object contains all of the data contained in the HTTP request.

Getting started
---------------

Download Oak and see the included example app. It has explanations in the comments. There's also a blank app that contains the default minimal `init.php` and `.htaccess` files. The blank app is less than 1 kB, while the entire `oak` namespace is about 10 kB.

License and authors
-------------------

Copyright (c) 2011 Marek Z.

Released under the MIT License. See MIT-LICENSE.txt.
