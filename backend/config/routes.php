<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;


return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Swagger', 'action' => 'index']);
        $builder->fallbacks();
    });

    $routes->scope('/api', function (RouteBuilder $builder): void {
        $builder->applyMiddleware();
        $builder->setExtensions(['json']);
        
        $builder->get('/pacientes', ['controller' => 'Pacientes', 'action' => 'index']);
        $builder->get('/pacientes/options', ['controller' => 'Pacientes', 'action' => 'options']);
        $builder->post('/pacientes', ['controller' => 'Pacientes', 'action' => 'add']);
        $builder->get('/pacientes/{id}', ['controller' => 'Pacientes', 'action' => 'view'])->setPass(['id']);
        $builder->put('/pacientes/{id}', ['controller' => 'Pacientes', 'action' => 'edit'])->setPass(['id']);
        $builder->delete('/pacientes/{id}', ['controller' => 'Pacientes', 'action' => 'delete'])->setPass(['id']);

        $builder->get('/medicos', ['controller' => 'Medicos', 'action' => 'index']);
        $builder->get('/medicos/options', ['controller' => 'Medicos', 'action' => 'options']);
        $builder->post('/medicos', ['controller' => 'Medicos', 'action' => 'add']);
        $builder->get('/medicos/{id}', ['controller' => 'Medicos', 'action' => 'view'])->setPass(['id']);
        $builder->put('/medicos/{id}', ['controller' => 'Medicos', 'action' => 'edit'])->setPass(['id']);
        $builder->delete('/medicos/{id}', ['controller' => 'Medicos', 'action' => 'delete'])->setPass(['id']);

    });

};
