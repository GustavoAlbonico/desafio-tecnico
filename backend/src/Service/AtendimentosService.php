<?php 

namespace App\Service;

use App\Error\Exception\EntityValidationException;
use App\Model\Entity\Atendimento;
use App\Repository\AtendimentosRepository;
use App\Service\Interface\IService;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Http\Exception\NotFoundException;

class AtendimentosService implements IService {

    public function __construct(private AtendimentosRepository $atendimentosRepository) {
    }

    public function list(): PaginatedInterface{
        return $this->atendimentosRepository->findAll();
    }

    public function findById(int $id): ?Atendimento{
        return $this->atendimentosRepository->findById($id);
    }

    public function create(array $data): Atendimento | bool{
        $atendimentoEntity = $this->atendimentosRepository->patchEntity(null,$data);

        if($atendimentoEntity->hasErrors()){
            throw new EntityValidationException("Entidade Atendimento está inválida, favor verificar!",$atendimentoEntity->getErrors());
        }

        return $this->atendimentosRepository->create($atendimentoEntity);
    }

    public function update(int $id, array $data): Atendimento | bool{
        $atendimento = $this->atendimentosRepository->findById($id);

        if(!$atendimento){
            throw new NotFoundException("Atendimento com id {$id} não encontrado, favor verificar!", 404);
        }

        $atendimentoEntity = $this->atendimentosRepository->patchEntity($atendimento, $data);

        if($atendimentoEntity->hasErrors()){
            throw new EntityValidationException("Entidade Atendimento está inválida, favor verificar!",$atendimentoEntity->getErrors());
        }

        return $this->atendimentosRepository->update($atendimentoEntity);
    }

    public function delete(int $id): bool{
        $atendimentoEntity = $this->atendimentosRepository->findById($id);

        if(!$atendimentoEntity){
            throw new NotFoundException("Atendimento com id {$id} não encontrado, favor verificar!", 404);
        }

        return $this->atendimentosRepository->delete($atendimentoEntity);
    }
}