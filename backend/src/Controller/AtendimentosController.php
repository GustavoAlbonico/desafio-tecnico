<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\AtendimentosService;

class AtendimentosController extends ApiController
{

    public function index(AtendimentosService $atendimentosService)
    {
        $atendimentos = $atendimentosService->list();
        return $this->ok($atendimentos, 'Atendimentos encontrados');
    }

    public function view(AtendimentosService $atendimentosService, int $id)
    {
        $atendimentoResponse = $atendimentosService->findById($id);

        if (!$atendimentoResponse) {
            return $this->badRequest('Não foi possível encontrar o atendimento!');
        }

        return $this->ok($atendimentoResponse, 'Atendimento encontrado');
    }

    public function add(AtendimentosService $atendimentosService)
    {
        $atendimentoRequest = $this->request->getData();
        $atendimentoResponse = $atendimentosService->create($atendimentoRequest);

        if (!$atendimentoResponse) {
            return $this->badRequest('Não foi possível criar o atendimento!');
        }

        return $this->created($atendimentoResponse, 'Atendimento criado com sucesso!');
    }

    public function edit(AtendimentosService $atendimentosService, int $id)
    {
        $atendimentoRequest = $this->request->getData();
        $atendimentoResponse = $atendimentosService->update($id, $atendimentoRequest);

        if (!$atendimentoResponse) {
            return $this->badRequest('Não foi possível editar o atendimento!');
        }

        return $this->ok($atendimentoResponse, 'Atendimento editado com sucesso!');
    }

    public function delete(AtendimentosService $atendimentosService, int $id)
    {
        $isDeleted = $atendimentosService->delete($id);

        if (!$isDeleted) {
            return $this->badRequest('Não foi possível excluir o atendimento!');
        }

        return $this->ok(message: 'Atendimento excluído com sucesso!');
    }
}