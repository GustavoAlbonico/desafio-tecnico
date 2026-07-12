<?php 

namespace App\Repository;

use App\Model\Entity\Medico;
use App\Model\Table\MedicosTable;
use App\Repository\Interface\IRepository;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\NumericPaginator;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\ORM\TableRegistry;

class MedicosRepository implements IRepository {

    private MedicosTable $table;

    public function __construct(private NumericPaginator $paginator)
    {
        $this->table = TableRegistry::getTableLocator()->get('Medicos');
    }

    public function findAll(): PaginatedInterface
    {
       return $this->paginator->paginate($this->table->find());
    }

    public function findAllAsOptions(): array
    {
       return $this->table->find('list')->toArray();
    }

    public function findById(int $id): ?Medico
    {
        return $this->table->find()->where(['id' => $id])->first();
    }

    public function create(EntityInterface $entity): Medico | bool
    {
        return $this->table->save($entity);
    }

    public function update(EntityInterface $entity): Medico | bool
    {   
        return $this->table->save($entity);
    }

    public function delete(EntityInterface $entity): bool
    {
        return $this->table->delete($entity);
    }

    public function patchEntity(?EntityInterface $entity,array $data): Medico{

        if(!$entity){
            $entity = $this->table->newEmptyEntity();
        }
        
        return $this->table->patchEntity($entity, $data);
    }
}