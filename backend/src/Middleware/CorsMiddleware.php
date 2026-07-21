<?php

namespace App\Middleware;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    if ($request->getMethod() === 'OPTIONS') {
      $response = new Response();
      $response = $response->cors($request)
        ->allowOrigin(['http://localhost:4200','https://localhost:4200'])
        ->allowMethods(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'])
        ->allowHeaders(['Content-Type', 'Authorization'])
        ->allowCredentials()
        ->maxAge(3600)
        ->build();

      return $response;
    }

    $response = $handler->handle($request);

    if ($response instanceof Response) {
        return $response->cors($request)
            ->allowOrigin(['http://localhost:4200','https://localhost:4200'])
            ->allowCredentials()
            ->build();
    }

    return $response;
  }
}
