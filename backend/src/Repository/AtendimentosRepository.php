<?php 

namespace App\Repository;

use App\Model\Entity\Atendimento;
use App\Model\Table\AtendimentosTable;
use App\Repository\Interface\IRepository;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\NumericPaginator;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\ORM\TableRegistry;

class AtendimentosRepository implements IRepository {

    private AtendimentosTable $table;
    private array $paginate;
    private array $filters;

    public function __construct(private NumericPaginator $paginator)
    {
        $this->table = TableRegistry::getTableLocator()->get('Atendimentos');
    }

    public function findAll(): PaginatedInterface
    {

        $query = $this->table->find()
        ->select([
            'Atendimentos.id',
            'Atendimentos.data_atendimento',
            'Atendimentos.valor_consulta',
            'Atendimentos.status',
            'medico_nome' => 'Medicos.nome',
            'paciente_nome' => 'Pacientes.nome'
        ])
        ->where($this->filters)
        ->contain([
            'Medicos',
            'Pacientes',
        ]);

        return $this->paginator->paginate(
            $query,$this->paginate,
            ['sortableFields' => ['data_atendimento', 'status' ,'valor_consulta', 'medico_nome', 'paciente_nome']]
        );
    }

    public function findById(int $id): ?Atendimento
    {
        return $this->table->find()->where(['id' => $id])->first();
    }

    public function create(EntityInterface $entity): Atendimento | bool
    {
        return $this->table->save($entity);
    }

    public function update(EntityInterface $entity): Atendimento | bool
    {   
        return $this->table->save($entity);
    }

    public function delete(EntityInterface $entity): bool
    {
        return $this->table->delete($entity);
    }

    public function patchEntity(?EntityInterface $entity,array $data): Atendimento{

        if(!$entity){
            $entity = $this->table->newEmptyEntity();
        }
        
        return $this->table->patchEntity($entity, $data);
    }

    public function existsByPacienteId(int $id) : bool {
        return $this->table->exists(['paciente_id' => $id]);
    }

    public function existsByMedicoId(int $id) : bool {
        return $this->table->exists(['medico_id' => $id]);
    }

    public function paginate(array $paginate):self{
        $this->paginate = $paginate;
        return $this;
    }

    public function filters(array $filters):self{
        $this->filters = $filters;
        return $this;
    }
}