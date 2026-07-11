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
        $status = $this->getHttpCode($exception);

        return (new Response())
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody(
                json_encode([
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'errors' => null,
                ])
            );
    }

    protected function getHttpCode(Throwable $exception): int
    {
        return method_exists($exception, 'getCode')
            && $exception->getCode() >= 400
            && $exception->getCode() < 600
                ? $exception->getCode()
                : 500;
    }
}