<?php
declare(strict_types=1);

namespace App\Controller;


use App\Service\PacientesService;
use SwaggerBake\Lib\Attribute\OpenApiOperation;
use SwaggerBake\Lib\Attribute\OpenApiPaginator;
use SwaggerBake\Lib\Attribute\OpenApiResponse;

class PacientesController extends ApiController
{

    public function index(PacientesService $pacienteService)
    {
        $pacientes = $pacienteService->list();
        return $this->ok($pacientes, 'Pacientes encontrados');
    }

    public function options(PacientesService $pacienteService)
    {
        $pacientesAsOptions = $pacienteService->listAsOptions();
        return $this->ok($pacientesAsOptions, 'Pacientes encontrados');
    }

    public function view(PacientesService $pacienteService, int $id)
    {
        $pacienteResponse = $pacienteService->findById($id);

        if (!$pacienteResponse) {
            return $this->badRequest('Não foi possível encontrar o paciente!');
        }

        return $this->ok($pacienteResponse, 'Paciente encontrado');
    }

    public function add(PacientesService $pacienteService)
    {
        $pacienteRequest = $this->request->getData();
        $pacienteResponse = $pacienteService->create($pacienteRequest);

        if (!$pacienteResponse) {
            return $this->badRequest('Não foi possível criar o paciente!');
        }

        return $this->created($pacienteResponse, 'Paciente criado com sucesso!');
    }

    public function edit(PacientesService $pacienteService, int $id)
    {
        $pacienteRequest = $this->request->getData();
        $pacienteResponse = $pacienteService->update($id, $pacienteRequest);

        if (!$pacienteResponse) {
            return $this->badRequest('Não foi possível editar o paciente!');
        }

        return $this->ok($pacienteResponse, 'Paciente editado com sucesso!');
    }

    public function delete(PacientesService $pacienteService, int $id)
    {
        $isDeleted = $pacienteService->delete($id);

        if (!$isDeleted) {
            return $this->badRequest('Não foi possível excluir o paciente!');
        }

        return $this->ok(message: 'Paciente excluído com sucesso!');
    }
}