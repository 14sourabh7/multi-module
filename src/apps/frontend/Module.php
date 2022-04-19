<?php

namespace Multiple\Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\ModuleDefinitionInterface;


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
                'Multiple\Frontend\Controllers' => '../apps/frontend/controllers/',
                'Multiple\Frontend\Models' => '../apps/frontend/models/',
                "App\Components" => "../apps/components",

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

            $eventManager = new Manager();

            // Attach a event listener to the dispatcher (if any)
            // For example:
            // $eventManager->attach('dispatch', new \My\Awesome\Acl('frontend'));

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace('Multiple\Frontend\Controllers\\');
            return $dispatcher;
        });

        // Registering the view component
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('../apps/frontend/views/');
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
    }
}
