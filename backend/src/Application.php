<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Error\ApiExceptionRenderer;
use App\Middleware\CorsMiddleware;
use App\Repository\AtendimentosRepository;
use App\Repository\MedicosRepository;
use App\Repository\PacientesRepository;
use App\Service\AtendimentosService;
use App\Service\MedicosService;
use App\Service\PacientesService;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Datasource\Paging\NumericPaginator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use League\Container\ReflectionContainer;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Load more plugins here
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
          ->add(new CorsMiddleware())
          ->add(
                new ErrorHandlerMiddleware(
                    ['exceptionRenderer' => ApiExceptionRenderer::class] + Configure::read('Error'),
                    $this
                )
            )
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))
            ->add(new BodyParserMiddleware());

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {

        /*--- Pagination ---*/

        $container->add(NumericPaginator::class);

        /*--- Pacientes ---*/

        $container->add(PacientesRepository::class)
            ->addArgument(NumericPaginator::class);

        $container->add(PacientesService::class)
            ->addArgument(PacientesRepository::class)
            ->addArgument(AtendimentosRepository::class);

        /*--- Medicos ---*/

        $container->add(MedicosRepository::class)
            ->addArgument(NumericPaginator::class);

        $container->add(MedicosService::class)
            ->addArgument(MedicosRepository::class)
            ->addArgument(AtendimentosRepository::class);

        /*--- Atendimentos ---*/

        $container->add(AtendimentosRepository::class)
            ->addArgument(NumericPaginator::class);

        $container->add(AtendimentosService::class)
            ->addArgument(AtendimentosRepository::class);

        /*--- Swagger ---*/

        $container->add(\Cake\Controller\ComponentRegistry::class);

        $container->delegate(new ReflectionContainer());

    }

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Bake');
        $this->addPlugin('Migrations');
        $this->addPlugin('SwaggerBake');
    }
}
