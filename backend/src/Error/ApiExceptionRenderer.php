<?php

namespace App\Error;

use Cake\Http\Response;
use Cake\Error\Renderer\WebExceptionRenderer;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiExceptionRenderer extends WebExceptionRenderer
{
    public function render(): ResponseInterface
    {
        $exception = $this->error;
        $code = $this->getHttpCode($exception);

        $response = new Response();
        $response = $response->withStatus($code);
        $response = $response->withType('application/json');
        $response = $response->withStringBody(json_encode([
            'error' => true,
            'message' => $exception->getMessage(),
            'code' => $code,
        ]));

        return $response;
    }

    protected function getHttpCode(Throwable $exception): int
    {
        return method_exists($exception, 'getCode') && 
        $exception->getCode() >= 400 && $exception->getCode() < 600
            ? $exception->getCode()
            : 500;
    }
}