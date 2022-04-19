<?php

namespace Multiple\Admin;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Session\Adapter\Stream;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                'Multiple\Admin\Controllers' => '../apps/admin/controllers/',
                'Multiple\Admin\Models'      => '../apps/admin/models/',
                'Multiple\Admin\Plugins'     => '../apps/admin/plugins/',
                'App\Components' => '../apps/components/',
                'Admin\Components' => '../apps/admin/components'
            ]
        );

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        // Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Multiple\Admin\Controllers\\');
            return $dispatcher;
        });

        // Registering the view component
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('../apps/admin/views/');
            return $view;
        });

        $di->set(
            'mongo',
            function () {
                $mongo = new \MongoDB\Client("mongodb+srv://m001-student:12345@sandbox.h1mpq.mongodb.net/myFirstDatabase?retryWrites=true&w=majority");

                return $mongo;
            },
            true
        );

        //db helper
        $di->set('dbHelper', function () {
            return new \App\Components\MongoHelper();
        }, true);
        $di->set(
            'locale',
            (new \App\Components\Locale())->getTranslator()
        );
        $di->set('escaper', function () {
            return new \Admin\Components\MyEscaper();
        }, true);

        $di->set('logger', function () {
            return new \Admin\Components\MyLogger();
        }, true);

        //session
        $di->set(
            'session',
            function () {
                $session = new SessionManager();
                $files = new Stream(
                    [
                        'savePath' => '/tmp',
                    ]
                );
                $session->setAdapter($files);
                $session->start();
                return $session;
            }
        );
    }
}
