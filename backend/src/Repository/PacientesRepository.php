<?php 

namespace App\Repository;

use App\Model\Entity\Paciente;
use App\Model\Table\PacientesTable;
use App\Repository\Interface\IRepository;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\NumericPaginator;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\TableRegistry;

class PacientesRepository implements IRepository {

    private PacientesTable $table;
    private array $paginate;

    public function __construct(private NumericPaginator $paginator)
    {
        $this->table = TableRegistry::getTableLocator()->get('Pacientes');
    }

    public function findAll(): PaginatedInterface
    {
        return $this->paginator->paginate($this->table->find(),$this->paginate);
    }

    public function findAllAsOptions(): ResultSetInterface
    {
       return $this->table->find()->select(['id','nome'])->all();
    }

    public function findById(int $id): ?Paciente
    {
        return $this->table->find()->where(['id' => $id])->first();
    }

    public function create(EntityInterface $entity): Paciente | bool
    {
        return $this->table->save($entity);
    }

    public function update(EntityInterface $entity): Paciente | bool
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

    public function paginate(array $paginate):self{
        $this->paginate = $paginate;
        return $this;
    }

}