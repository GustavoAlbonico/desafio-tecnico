<?php
declare(strict_types=1);

namespace App\Controller;


use OpenApi\Attributes as OA;
use App\Service\AtendimentosService;
use SwaggerBake\Lib\Attribute\OpenApiOperation;
use SwaggerBake\Lib\Attribute\OpenApiPaginator;
use SwaggerBake\Lib\Attribute\OpenApiQueryParam;
use SwaggerBake\Lib\Attribute\OpenApiResponse;

class AtendimentosController extends ApiController
{
    #[OpenApiOperation(summary: 'Lista todos os atendimentos cadastrados')]

    #[OpenApiQueryParam(name: 'paciente_id',type: 'integer',description: 'Filtra por paciente')]
    #[OpenApiQueryParam(name: 'medico_id', type: 'integer',description: 'Filtra por médico')]
    #[OpenApiPaginator(sortEnum: ['data_nascimento','valor_consulta','status','paciente_id','medico_id'])]
    #[OpenApiResponse( statusCode: '200', description: 'Atendimentos encontrados com sucesso', ref: '#/components/schemas/ApiAtendimentosListResponse' )]
    #[OpenApiResponse( statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function index(AtendimentosService $atendimentosService)
    {
        $paginateParams =  $this->getPaginateParams();
        $filtersParams =  $this->getFiltersParams();

        $atendimentos = $atendimentosService
            ->filters($filtersParams)
            ->paginate($paginateParams)
            ->list();

        return $this->ok($atendimentos, 'Atendimentos encontrados');
    }

    #[OpenApiOperation(summary: 'Busca um atendimento pelo ID')]
    #[OpenApiResponse(statusCode: '200', description: 'Atendimento encontrado com sucesso', ref: '#/components/schemas/ApiAtendimentoResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Atendimento não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function view(AtendimentosService $atendimentosService, int $id)
    {
        $atendimentoResponse = $atendimentosService->findById($id);

        if (!$atendimentoResponse) {
            return $this->badRequest('Não foi possível encontrar o atendimento!');
        }

        return $this->ok($atendimentoResponse, 'Atendimento encontrado');
    }

    #[OpenApiOperation(summary: 'Cadastra um novo atendimento')]
    #[OpenApiResponse(statusCode: '201', description: 'Atendimento criado com sucesso', ref: '#/components/schemas/ApiAtendimentoResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível criar o atendimento', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '422', description: 'Dados inválidos', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function add(AtendimentosService $atendimentosService)
    {
        $atendimentoRequest = $this->request->getData();
        $atendimentoResponse = $atendimentosService->create($atendimentoRequest);

        if (!$atendimentoResponse) {
            return $this->badRequest('Não foi possível criar o atendimento!');
        }

        return $this->created($atendimentoResponse, 'Atendimento criado com sucesso!');
    }

    #[OpenApiOperation(summary: 'Atualiza um atendimento existente')]
    #[OpenApiResponse(statusCode: '200', description: 'Atendimento editado com sucesso', ref: '#/components/schemas/ApiAtendimentoResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível editar o atendimento', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Atendimento não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '422', description: 'Dados inválidos', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function edit(AtendimentosService $atendimentosService, int $id)
    {
        $atendimentoRequest = $this->request->getData();
        $atendimentoResponse = $atendimentosService->update($id, $atendimentoRequest);

        if (!$atendimentoResponse) {
            return $this->badRequest('Não foi possível editar o atendimento!');
        }

        return $this->ok($atendimentoResponse, 'Atendimento editado com sucesso!');
    }

    #[OpenApiOperation(summary: 'Remove um atendimento')]
    #[OpenApiResponse(statusCode: '200', description: 'Atendimento removido com sucesso', ref: '#/components/schemas/ApiAtendimentoResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível excluir o atendimento', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Atendimento não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function delete(AtendimentosService $atendimentosService, int $id)
    {
        $isDeleted = $atendimentosService->delete($id);

        if (!$isDeleted) {
            return $this->badRequest('Não foi possível excluir o atendimento!');
        }

        return $this->ok(message: 'Atendimento excluído com sucesso!');
    }

    private function getPaginateParams():array {
        return [
            'page' => $this->request->getQuery('page') ?: 1,
            'sort' => $this->request->getQuery('sort') ?: 'data_atendimento',
            'direction' => $this->request->getQuery('direction') ?: 'DESC',
            'limit' => $this->request->getQuery('limit') ?: 20
        ];
    }

    private function getFiltersParams():array {

        $medicoId = $this->request->getQuery('medico_id') ?: null;
        $pacienteId = $this->request->getQuery('paciente_id') ?: null;
        $status = $this->request->getQuery('status') ?: null;
        $dataInicial = $this->request->getQuery('data_inicial') ?: null;
        $dataFinal = $this->request->getQuery('data_final') ?: null;

        return  array_filter([
            'Atendimentos.medico_id' => $medicoId,
            'Atendimentos.paciente_id' => $pacienteId,
            'Atendimentos.status' => $status,
            'Atendimentos.data_atendimento >=' => $dataInicial,
            'Atendimentos.data_atendimento <=' => $dataFinal,
        ]);
    }
}