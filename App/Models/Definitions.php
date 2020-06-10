<?php

namespace App\Models;


use Aura\SqlQuery\QueryFactory;
use DB\Connection;
use Delight\Auth\Auth;
use DI\ContainerBuilder;
use League\Plates\Engine;
use Valitron\Validator;

class Definitions
{
    public static function addDefinitions()
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->addDefinitions([
            Engine::class => function(){
                return new Engine('../App/views');
            },
            QueryFactory::class => function(){
                return new QueryFactory('mysql');
            },
            \PDO::class => function(){
                return Connection::make();
            },
            Auth::class => function($container){
                return new Auth($container->get('PDO'));
            }
        ]);
        return $containerBuilder;
    }
}