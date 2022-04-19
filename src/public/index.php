<?php

error_reporting(E_ALL);

use Phalcon\Loader;
use Phalcon\Mvc\Router;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    protected function registerServices()
    {

        $di = new FactoryDefault();

        $loader = new Loader();

        define('BASE_PATH', dirname(__DIR__));
        define('APP_PATH', BASE_PATH . '/app');

        require BASE_PATH . '/vendor/autoload.php';


        /**
         * We're a registering a set of directories taken from the configuration file
         */
        $loader
            ->registerDirs([__DIR__ . '/../apps/library/'])->register();

        // Registering a router
        $di->set('router', function () {

            $router = new Router();

            $router->setDefaultModule("frontend");

            $router->add('/:controller/:action', [
                'module'     => 'frontend',
                'controller' => 1,
                'action'     => 2,
            ])->setName('frontend');

            $router->add("/login", [
                'module'     => 'admin',
                'controller' => 'login',
                'action'     => 'index',
            ])->setName('admin-login');

            $router->add("/logout", [
                'module'     => 'admin',
                'controller' => 'login',
                'action'     => 'logout',
            ])->setName('admin-logout');

            $router->add("/admin/products/:action", [
                'module'     => 'admin',
                'controller' => 'products',
                'action'     => 1,
            ])->setName('admin-product');

            $router->add("/admin/products", [
                'module'     => 'admin',
                'controller' => 'products',
                'action'     => 'index',
            ])->setName('admin-products');


            return $router;
        });




        $this->setDI($di);
    }

    public function main()
    {

        $this->registerServices();

        // Register the installed modules
        $this->registerModules([
            'frontend' => [
                'className' => 'Multiple\Frontend\Module',
                'path'      => '../apps/frontend/Module.php'
            ],
            'admin'  => [
                'className' => 'Multiple\Admin\Module',
                'path'      => '../apps/admin/Module.php'
            ]
        ]);

        $response = $this->handle($this->request->getURI());

        $response->send();
    }
}

$application = new Application();
$application->main();
