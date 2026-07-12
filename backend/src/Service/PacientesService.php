<?php 

namespace App\Service;

use App\Error\Exception\EntityValidationException;
use App\Model\Entity\Paciente;
use App\Repository\PacientesRepository;
use App\Service\Interface\IService;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Http\Exception\NotFoundException;

class PacientesService implements IService {

    public function __construct(private PacientesRepository $pacientesRepository) {
    }

    public function list(): PaginatedInterface{
        return $this->pacientesRepository->findAll();
    }

    public function listAsOptions(): array {
        return $this->pacientesRepository->findAllAsOptions();
    }

    public function findById(int $id): ?Paciente{
        return $this->pacientesRepository->findById($id);
    }

    public function create(array $data): Paciente | bool{
        $pacienteEntity = $this->pacientesRepository->patchEntity(null,$data);

        if($pacienteEntity->hasErrors()){
            throw new EntityValidationException("Entidade Paciente está inválida, favor verificar!",$pacienteEntity->getErrors());
        }

        return $this->pacientesRepository->create($pacienteEntity);
    }

    public function update(int $id, array $data): Paciente | bool{
        $paciente = $this->pacientesRepository->findById($id);

        if(!$paciente){
            throw new NotFoundException("Paciente com id {$id} não encontrado, favor verificar!", 404);
        }

        $pacienteEntity = $this->pacientesRepository->patchEntity($paciente, $data);

        if($pacienteEntity->hasErrors()){
            throw new EntityValidationException("Entidade Paciente está inválida, favor verificar!",$pacienteEntity->getErrors());
        }

        return $this->pacientesRepository->update($pacienteEntity);
    }

    public function delete(int $id): bool{
        $pacienteEntity = $this->pacientesRepository->findById($id);

        if(!$pacienteEntity){
            throw new NotFoundException("Paciente com id {$id} não encontrado, favor verificar!", 404);
        }

        return $this->pacientesRepository->delete($pacienteEntity);
    }
}