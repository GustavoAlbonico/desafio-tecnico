<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\MedicosService;

use SwaggerBake\Lib\Attribute\OpenApiOperation;
use SwaggerBake\Lib\Attribute\OpenApiPaginator;
use SwaggerBake\Lib\Attribute\OpenApiResponse;

class MedicosController extends ApiController
{

    #[OpenApiOperation(summary: 'Lista todos os médicos cadastrados')]
    #[OpenApiPaginator(sortEnum: ['nome'])]
    #[OpenApiResponse( statusCode: '200', description: 'Médicos encontrados com sucesso', ref: '#/components/schemas/ApiMedicosListResponse' )]
    #[OpenApiResponse( statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function index(MedicosService $medicosService)
    {
        $medicos = $medicosService->list();
        return $this->ok($medicos, 'Médicos encontrados');
    }

    #[OpenApiOperation(summary: 'Lista todos os médicos com chave e valor para select')]
    #[OpenApiResponse( statusCode: '200', description: 'Médicos encontrados com sucesso', ref: '#/components/schemas/ApiListOfOptionsResponse' )]
    #[OpenApiResponse( statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function options(MedicosService $medicosService)
    {
        $medicosAsOptions = $medicosService->listAsOptions();
        return $this->ok($medicosAsOptions, 'Médicos encontrados');
    }

    #[OpenApiOperation(summary: 'Busca um médico pelo ID')]
    #[OpenApiResponse(statusCode: '200', description: 'Médicos encontrado com sucesso', ref: '#/components/schemas/ApiSuccessResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Médicos não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function view(MedicosService $medicosService, int $id)
    {
        $medicoResponse = $medicosService->findById($id);

        if (!$medicoResponse) {
            return $this->badRequest('Não foi possível encontrar o medico!');
        }

        return $this->ok($medicoResponse, 'Médico encontrado');
    }

    #[OpenApiOperation(summary: 'Cadastra um novo médico')]
    #[OpenApiResponse(statusCode: '201', description: 'Médicos criado com sucesso', ref: '#/components/schemas/ApiMedicosResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível criar o médico', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '422', description: 'Dados inválidos', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function add(MedicosService $medicosService)
    {
        $medicoRequest = $this->request->getData();
        $medicoResponse = $medicosService->create($medicoRequest);

        if (!$medicoResponse) {
            return $this->badRequest('Não foi possível criar o medico!');
        }

        return $this->created($medicoResponse, 'Médico criado com sucesso!');
    }

    #[OpenApiOperation(summary: 'Atualiza um médico existente')]
    #[OpenApiResponse(statusCode: '200', description: 'Médicos editado com sucesso', ref: '#/components/schemas/ApiMedicosResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível editar o médico', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Médicos não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '422', description: 'Dados inválidos', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function edit(MedicosService $medicosService, int $id)
    {
        $medicoRequest = $this->request->getData();
        $medicoResponse = $medicosService->update($id, $medicoRequest);

        if (!$medicoResponse) {
            return $this->badRequest('Não foi possível editar o medico!');
        }

        return $this->ok($medicoResponse, 'Médico editado com sucesso!');
    }

    #[OpenApiOperation(summary: 'Remove um médico')]
    #[OpenApiResponse(statusCode: '200', description: 'Médicos removido com sucesso', ref: '#/components/schemas/ApiMedicosResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível excluir o médico', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Médicos não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function delete(MedicosService $medicosService, int $id)
    {
        $isDeleted = $medicosService->delete($id);

        if (!$isDeleted) {
            return $this->badRequest('Não foi possível excluir o medico!');
        }

        return $this->ok(message: 'Médico excluído com sucesso!');
    }
}