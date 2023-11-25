<?php

// setup v8 class autoloading
$classLoader = new \Symfony\Component\ClassLoader\Psr4ClassLoader();
$classLoader->addPrefix('Application', DIR_APPLICATION . '/' . DIRNAME_CLASSES);
$classLoader->register();
// register the "bootstrap5" grid framework
$manager = Core::make('manager/grid_framework');
$manager->extend('bootstrap5', function($app) {
    return new Application\Bootstrap5GridFramework();
});
