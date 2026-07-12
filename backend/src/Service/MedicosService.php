<?php 

namespace App\Service;

use App\Error\Exception\EntityValidationException;
use App\Model\Entity\Medico;
use App\Repository\MedicosRepository;
use App\Service\Interface\IService;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Http\Exception\NotFoundException;

class MedicosService implements IService {

    public function __construct(private MedicosRepository $medicosRepository) {
    }

    public function list(): PaginatedInterface{
        return $this->medicosRepository->findAll();
    }

    public function listAsOptions(): array {
        return $this->medicosRepository->findAllAsOptions();
    }

    public function findById(int $id): ?Medico{
        return $this->medicosRepository->findById($id);
    }

    public function create(array $data): Medico | bool{
        $medicoEntity = $this->medicosRepository->patchEntity(null,$data);

        if($medicoEntity->hasErrors()){
            throw new EntityValidationException("Entidade Médico está inválida, favor verificar!",$medicoEntity->getErrors());
        }

        return $this->medicosRepository->create($medicoEntity);
    }

    public function update(int $id, array $data): Medico | bool{
        $paciente = $this->medicosRepository->findById($id);

        if(!$paciente){
            throw new NotFoundException("Médico com id {$id} não encontrado, favor verificar!", 404);
        }

        $medicoEntity = $this->medicosRepository->patchEntity($paciente, $data);

        if($medicoEntity->hasErrors()){
            throw new EntityValidationException("Entidade Médico está inválida, favor verificar!",$medicoEntity->getErrors());
        }

        return $this->medicosRepository->update($medicoEntity);
    }

    public function delete(int $id): bool{
        $medicoEntity = $this->medicosRepository->findById($id);

        if(!$medicoEntity){
            throw new NotFoundException("Médico com id {$id} não encontrado, favor verificar!", 404);
        }

        return $this->medicosRepository->delete($medicoEntity);
    }
}