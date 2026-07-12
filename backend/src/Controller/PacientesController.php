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
    public function index(PacientesService $pacienteService)
    {
        $pacientes = $pacienteService->list();
        return $this->ok($pacientes, 'Pacientes encontrados');
    }

    #[OpenApiOperation(summary: 'Lista todos os pacientes com chave e valor para select')]
    #[OpenApiResponse( statusCode: '200', description: 'Pacientes encontrados com sucesso', ref: '#/components/schemas/ApiSuccessResponse' )]
    #[OpenApiResponse( statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function options(PacientesService $pacienteService)
    {
        $pacientesAsOptions = $pacienteService->listAsOptions();
        return $this->ok($pacientesAsOptions, 'Pacientes encontrados');
    }

    #[OpenApiOperation(summary: 'Busca um paciente pelo ID')]
    #[OpenApiResponse(statusCode: '200', description: 'Paciente encontrado com sucesso', ref: '#/components/schemas/ApiSuccessResponse')]
    #[OpenApiResponse(statusCode: '404', description: 'Paciente não encontrado', ref: '#/components/schemas/ApiErrorResponse')]
    #[OpenApiResponse(statusCode: '500', description: 'Ocorreu um erro inesperado', ref: '#/components/schemas/ApiErrorResponse')]
    public function view(PacientesService $pacienteService, int $id)
    {
        $pacienteResponse = $pacienteService->findById($id);

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
    public function add(PacientesService $pacienteService)
    {
        $pacienteRequest = $this->request->getData();
        $pacienteResponse = $pacienteService->create($pacienteRequest);

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
    public function edit(PacientesService $pacienteService, int $id)
    {
        $pacienteRequest = $this->request->getData();
        $pacienteResponse = $pacienteService->update($id, $pacienteRequest);

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
    public function delete(PacientesService $pacienteService, int $id)
    {
        $isDeleted = $pacienteService->delete($id);

        if (!$isDeleted) {
            return $this->badRequest('Não foi possível excluir o paciente!');
        }

        return $this->ok(message: 'Paciente excluído com sucesso!');
    }
}