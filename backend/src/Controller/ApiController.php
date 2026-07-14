<?php

namespace App\Controller;

use Cake\Datasource\Paging\PaginatedResultSet;
use Cake\Event\EventInterface;
use Cake\Log\Log;

class ApiController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->request = $this->request->withParam('_ext', 'json');
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setClassName('Json');
    }

    protected function success(
        mixed $data = null,
        string $message = 'Sucesso',
        int $status = 200
    ): void {
        $this->response = $this->response->withStatus($status);

        $pagination = [];
        $paging = $data instanceof PaginatedResultSet ? $data->pagingParams() : null;

        if($paging){
            $pagination = [
                'pagination' => [
                    'current_page' => $paging['currentPage'],
                    'per_page' => $paging['perPage'],
                    'total' => $paging['totalCount'],
                    'total_pages' => $paging['pageCount'],
                    'has_next_page' => $paging['hasNextPage'],
                    'has_prev_page' => $paging['hasPrevPage']
                ]
            ];
       }

        $this->set(array_merge(
            [
                'success' => true,
                'message' => $message,
                'data' => $data,
            ],
            $pagination
        ));

        $this->viewBuilder()->setOption(
            'serialize',
            ['success', 'message', 'data','pagination']
        )->setOption('jsonOptions', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function error(
        string $message,
        mixed $errors = null,
        int $status = 500
    ): void {
        $this->response = $this->response->withStatus($status);

        Log::error($message, [$status,$errors]);

        $this->set([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ]);

        $this->viewBuilder()->setOption(
            'serialize',
            ['success', 'message', 'errors']
        )->setOption('jsonOptions', JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function ok(
        mixed $data = null,
        string $message = 'Sucesso'
    ): void {
        $this->success($data, $message, 200);
    }

    protected function created(
        mixed $data = null,
        string $message = 'Registro criado com sucesso'
    ): void {
        $this->success($data, $message, 201);
    }

    protected function noContent(): void
    {
        $this->response = $this->response->withStatus(204);
    }

    protected function badRequest(
        string $message = 'Requisição inválida',
        mixed $errors = null
    ): void {
        $this->error($message, $errors, 400);
    }

    protected function notFound(
        string $message = 'Registro não encontrado',
        mixed $errors = null
    ): void {
        $this->error($message, $errors, 404);
    }

    protected function unprocessableEntity(
        string $message = 'Erro de validação',
        mixed $errors = null
    ): void {
        $this->error($message, $errors, 422);
    }

    protected function internalError(
        string $message = 'Erro interno do servidor',
        mixed $errors = null
    ): void {
        $this->error($message, $errors, 500);
    }
}