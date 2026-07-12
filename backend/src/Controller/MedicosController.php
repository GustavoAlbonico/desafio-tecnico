<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\MedicosService;

class MedicosController extends ApiController
{
    public function index(MedicosService $medicosService)
    {
        $medicos = $medicosService->list();
        return $this->ok($medicos, 'Médicos encontrados');
    }

    public function options(MedicosService $medicosService)
    {
        $medicosAsOptions = $medicosService->listAsOptions();
        return $this->ok($medicosAsOptions, 'Médicos encontrados');
    }

    public function view(MedicosService $medicosService, int $id)
    {
        $medicoResponse = $medicosService->findById($id);

        if (!$medicoResponse) {
            return $this->badRequest('Não foi possível encontrar o medico!');
        }

        return $this->ok($medicoResponse, 'Médico encontrado');
    }

    public function add(MedicosService $medicosService)
    {
        $medicoRequest = $this->request->getData();
        $medicoResponse = $medicosService->create($medicoRequest);

        if (!$medicoResponse) {
            return $this->badRequest('Não foi possível criar o medico!');
        }

        return $this->created($medicoResponse, 'Médico criado com sucesso!');
    }

    public function edit(MedicosService $medicosService, int $id)
    {
        $medicoRequest = $this->request->getData();
        $medicoResponse = $medicosService->update($id, $medicoRequest);

        if (!$medicoResponse) {
            return $this->badRequest('Não foi possível editar o medico!');
        }

        return $this->ok($medicoResponse, 'Médico editado com sucesso!');
    }

    public function delete(MedicosService $medicosService, int $id)
    {
        $isDeleted = $medicosService->delete($id);

        if (!$isDeleted) {
            return $this->badRequest('Não foi possível excluir o medico!');
        }

        return $this->ok(message: 'Médico excluído com sucesso!');
    }
}