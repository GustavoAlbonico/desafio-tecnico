<?php
declare(strict_types=1);

namespace App\Controller;


use App\Service\PacientesService;
use SwaggerBake\Lib\Attribute\OpenApiOperation;
use SwaggerBake\Lib\Attribute\OpenApiPaginator;
use SwaggerBake\Lib\Attribute\OpenApiResponse;

class PacientesController extends ApiController
{
    #[OpenApiOperation(summary: 'Lista todos os pacientes cadastrados')]
    #[OpenApiPaginator(sortEnum: ['nome'])]
    #[OpenApiResponse( statusCode: '200', description: 'Pacientes encontrados com sucesso', ref: '#/components/schemas/ApiPacientesListResponse' )]
    #[OpenApiResponse( statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function index(PacientesService $pacientesService)
    {
        $pacientes = $pacientesService->list();
        return $this->ok($pacientes, 'Pacientes encontrados');
    }

    #[OpenApiOperation(summary: 'Lista todos os pacientes com chave e valor para select')]
    #[OpenApiResponse( statusCode: '200', description: 'Pacientes encontrados com sucesso', ref: '#/components/schemas/ApiListOfOptionsResponse' )]
    #[OpenApiResponse( statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function options(PacientesService $pacientesService)
    {
        $pacientesAsOptions = $pacientesService->listAsOptions();
        return $this->ok($pacientesAsOptions, 'Pacientes encontrados');
    }

    #[OpenApiOperation(summary: 'Busca um paciente pelo ID')]
    #[OpenApiResponse(statusCode: '200', description: 'Paciente encontrado com sucesso', ref: '#/components/schemas/ApiSuccessResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Paciente não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function view(PacientesService $pacientesService, int $id)
    {
        $pacienteResponse = $pacientesService->findById($id);

        if (!$pacienteResponse) {
            return $this->badRequest('Não foi possível encontrar o paciente!');
        }

        return $this->ok($pacienteResponse, 'Paciente encontrado');
    }

    #[OpenApiOperation(summary: 'Cadastra um novo paciente')]
    #[OpenApiResponse(statusCode: '201', description: 'Paciente criado com sucesso', ref: '#/components/schemas/ApiPacienteResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível criar o paciente', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '422', description: 'Dados inválidos', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function add(PacientesService $pacientesService)
    {
        $pacienteRequest = $this->request->getData();
        $pacienteResponse = $pacientesService->create($pacienteRequest);

        if (!$pacienteResponse) {
            return $this->badRequest('Não foi possível criar o paciente!');
        }

        return $this->created($pacienteResponse, 'Paciente criado com sucesso!');
    }

    #[OpenApiOperation(summary: 'Atualiza um paciente existente')]
    #[OpenApiResponse(statusCode: '200', description: 'Paciente editado com sucesso', ref: '#/components/schemas/ApiPacienteResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível editar o paciente', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Paciente não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '422', description: 'Dados inválidos', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function edit(PacientesService $pacientesService, int $id)
    {
        $pacienteRequest = $this->request->getData();
        $pacienteResponse = $pacientesService->update($id, $pacienteRequest);

        if (!$pacienteResponse) {
            return $this->badRequest('Não foi possível editar o paciente!');
        }

        return $this->ok($pacienteResponse, 'Paciente editado com sucesso!');
    }

    #[OpenApiOperation(summary: 'Remove um paciente')]
    #[OpenApiResponse(statusCode: '200', description: 'Paciente removido com sucesso', ref: '#/components/schemas/ApiPacienteResponse')]
    #[OpenApiResponse(statusCode: '400', description: 'Não foi possível excluir o paciente', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Paciente não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function delete(PacientesService $pacientesService, int $id)
    {
        $isDeleted = $pacientesService->delete($id);

        if (!$isDeleted) {
            return $this->badRequest('Não foi possível excluir o paciente!');
        }

        return $this->ok(message: 'Paciente excluído com sucesso!');
    }
}