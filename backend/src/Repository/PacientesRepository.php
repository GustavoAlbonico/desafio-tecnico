<?php 

namespace App\Repository;

use App\DTO\PacientesDTO;
use App\Model\Entity\Paciente;
use App\Model\Table\PacientesTable;
use App\Repository\Interface\IRepository;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\NumericPaginator;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\ORM\TableRegistry;

class PacientesRepository implements IRepository {

    private PacientesTable $table;

    public function __construct(private NumericPaginator $paginator)
    {
        $this->table = TableRegistry::getTableLocator()->get('Pacientes');
    }

    public function findAll(): PaginatedInterface
    {
       $pacientesDto = $this->table->find();
       return $this->paginator->paginate($pacientesDto);
    }

    public function findById(int $id): ?Paciente
    {
        return $this->table->get($id, contain: ['Atendimentos']);
    }

    public function create(EntityInterface $entity): bool
    {
        return $this->table->save($entity);
    }

    public function update(EntityInterface $entity): bool
    {   
        return $this->table->save($entity);
    }

    public function delete(EntityInterface $entity): bool
    {
        return $this->table->delete($entity);
    }

    public function patchEntity(?EntityInterface $entity,array $data): Paciente{

        if(!$entity){
            $entity = $this->table->newEmptyEntity();
        }
        
        return $this->table->patchEntity($entity, $data);
    }
}