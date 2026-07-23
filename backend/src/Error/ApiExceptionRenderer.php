<?php

namespace App\Error;

use App\Error\Exception\EntityValidationException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Database\Exception\DatabaseException;
use Cake\Http\Response;
use Cake\Error\Renderer\WebExceptionRenderer;
use Cake\Http\Exception\ConflictException;
use Cake\Log\Log;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Classe reponsável por pegar qualquer exception lançada dentro do sistema e
 * responder de forma amigavel sem expor nada critico
 */
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
                    'message' => $this->getSafeMessage($exception),
                    'errors' => $exception instanceof EntityValidationException ? $exception->getErrors() : null,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );
    }

    protected function getSafeMessage(Throwable $exception): string
    {
        Log::error($exception->getMessage(), ['exception' => $exception]);

        return match (true) {
            $exception instanceof DatabaseException ||
            $exception instanceof \PDOException     ||
            $exception->getPrevious() instanceof \PDOException => 'Não foi possível concluir a operação, erro relacionado ao banco de dados!',

            $exception instanceof EntityValidationException ||
            $exception instanceof \DomainException ||
            $exception instanceof RecordNotFoundException ||
            $exception instanceof ConflictException => $exception->getMessage(),

            default => 'Ocorreu um erro inesperado. Tente novamente mais tarde.'
        };
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