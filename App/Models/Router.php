<?php
namespace App\Models;

use FastRoute;

class Router{

    public static function getRouter(){
        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
			
            $r->addRoute('GET', '/sms/photoconfirm/{phone:\w+}', ['App\Controllers\SmsController', 'PhotoConfirmation']);
            $r->addRoute('GET', '/sms/costrecovery/{phone:\w+}', ['App\Controllers\SmsController', 'CostRecovery']);
            $r->addRoute('GET', '/sms/responsible/{id:\d+}', ['App\Controllers\SmsController', 'ResponsiblePerson']);
            $r->addRoute('GET', '/sms/taskcompletion/{phone:\w+}', ['App\Controllers\SmsController', 'TaskСompletion']);
			
			$r->addRoute('GET', '/admin/history/{AN:\d+}', ['App\Controllers\HistoryController', 'getOne']);
            $r->addRoute('GET', '/admin/history', ['App\Controllers\HistoryController', 'getAll']);
			
            $r->addRoute('POST', '/CallEvent', ['App\Controllers\MainController', 'cEvent']);
            $r->addRoute('GET', '/calls', ['App\Controllers\CallsController', 'Calls']);
            $r->addRoute('POST', '/calls', ['App\Controllers\CallsController', 'CallsPost']);
            $r->addRoute('GET', '/calls/{id:\d+}/edit', ['App\Controllers\CallsController', 'CallsEdit']);
            $r->addRoute('POST', '/calls/{id:\d+}/edit', ['App\Controllers\CallsController', 'CallsEditPost']);
			$r->addRoute('GET', '/calls/old/delete', ['App\Controllers\CallsController', 'OldDelete']);
			$r->addRoute('GET', '/call/{id:\d+}/delete', ['App\Controllers\CallsController', 'CallDelete']);
			
            $r->addRoute('GET', '/', ['App\Controllers\MainController', 'Index']);

            $r->addRoute('GET', '/auth', ['App\Controllers\UserController', 'Login']);
            $r->addRoute('POST', '/auth', ['App\Controllers\UserController', 'Login']);

            $r->addRoute('GET', '/verification', ['App\Controllers\UserController', 'verify_email']);

            $r->addRoute('GET', '/promocode/{rand_gen:\w+}', ['App\Controllers\PromocodeController', 'getCheckPromocod']);
            $r->addRoute('POST', '/getdiscount', ['App\Controllers\PromocodeController', 'createFile']);

            $r->addRoute('GET', '/logout', ['App\Controllers\UserController', 'Logout']);

            $r->addRoute('POST', '/', ['App\Controllers\AppealController', 'addAppeal']);

            $r->addRoute('POST', '/sendsms', ['App\Controllers\AppealController', 'SendSMSReplay']);

            $r->addRoute('GET', '/edit/{id:\d+}', ['App\Controllers\AppealController', 'manageAppeal']);
            $r->addRoute('POST', '/edit/{id:\d+}', ['App\Controllers\AppealController', 'editAppeal']);
            $r->addRoute('GET', '/delete/{id:\d+}', ['App\Controllers\AppealController', 'deleteAppeal']);

            $r->addRoute('GET', '/admin', ['App\Controllers\AdminController', 'Index']);
            $r->addRoute('GET', '/admin/cnfapi', ['App\Controllers\AdminController', 'cnfApi']);
            $r->addRoute('POST', '/admin/cnfapi', ['App\Controllers\AdminController', 'cnfApi']);

            $r->addRoute('GET', '/admin/register', ['App\Controllers\UserController', 'Register']);
			$r->addRoute('POST', '/admin/register', ['App\Controllers\UserController', 'Register']);


            $r->addRoute('POST', '/rolle/{userId:\d+}', ['App\Controllers\AdminController', 'assigningRolesToUsers']);
            $r->addRoute('GET', '/admin/profile/{userId:\d+}', ['App\Controllers\AdminController', 'getProfile']);
            $r->addRoute('POST', '/admin/profile/edit', ['App\Controllers\AdminController', 'ProfileUpdate']);
			$r->addRoute('POST', '/profile/delete', ['App\Controllers\AdminController', 'ProfileDelete']);
			
			$r->addRoute('POST', '/admin/profile/changepass', ['App\Controllers\AdminController', 'ChangeCurentUserPassword']);

        });

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];



        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                echo '... 404 Not Found';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                echo '... 405 Method Not Allowed';
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $containerBuilder = Definitions::addDefinitions();
                $container = $containerBuilder->build();
                $container->call($routeInfo[1], $routeInfo[2]);
                break;
        }
    }

}