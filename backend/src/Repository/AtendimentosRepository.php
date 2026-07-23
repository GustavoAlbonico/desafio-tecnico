<?php

namespace App\Repository;

use App\Model\Entity\Atendimento;
use App\Model\Table\AtendimentosTable;
use App\Repository\Interface\IRepository;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\NumericPaginator;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;

class AtendimentosRepository implements IRepository
{

    private AtendimentosTable $table;
    private array $paginate;
    private array $filters;

    /**
     * Instância a table para poder acessar o banco de dados via ORM
     *
     * @param NumericPaginator $paginator
     */
    public function __construct(private NumericPaginator $paginator)
    {
        $this->table = TableRegistry::getTableLocator()->get('Atendimentos');
    }

    /**
     * Busca todos os atendimentos passando filtros e opções de paginação
     * 
     * @return PaginatedInterface
     */
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
            $query,
            $this->paginate,
            ['sortableFields' => ['data_atendimento', 'status', 'valor_consulta', 'medico_nome', 'paciente_nome']]
        );
    }

    /**
     * Busca um registro por id informado
     *
     * @param integer $id
     * @return Atendimento|null
     */
    public function findById(int $id): ?Atendimento
    {
        return $this->table->find()->where(['id' => $id])->first();
    }

    /**
     * Cria um registro novo no banco de dados
     *
     * @param EntityInterface $entity
     * @return Atendimento | bool
     */
    public function create(EntityInterface $entity): Atendimento | bool
    {
        return $this->table->save($entity);
    }

    /**
     * Atualiza um registro no banco de dados
     *
     * @param EntityInterface $entity
     * @return Atendimento | bool
     */
    public function update(EntityInterface $entity): Atendimento | bool
    {
        return $this->table->save($entity);
    }

    /**
     * Deleta um registro do banco de dados
     *
     * @param EntityInterface $entity
     * @return boolean
     */
    public function delete(EntityInterface $entity): bool
    {
        return $this->table->delete($entity);
    }

    /**
     * Pega os dados do request e atribui para dentro de uma entidade
     * Passa pelos validators do banco de dados (model)
     *
     * @param EntityInterface|null $entity
     * @param array $data
     * @return Atendimento
     */
    public function patchEntity(?EntityInterface $entity, array $data): Atendimento
    {

        if (!$entity) {
            $entity = $this->table->newEmptyEntity();
        }

        return $this->table->patchEntity($entity, $data);
    }

    /**
     * Verifica se existe um atendimento com o id do paciente passado relacionado
     *
     * @param integer $id
     * @return boolean
     */
    public function existsByPacienteId(int $id): bool
    {
        return $this->table->exists(['paciente_id' => $id]);
    }

    /**
    * Verifica se existe um atendimento com o id do médico passado relacionado
     *
     * @param integer $id
     * @return boolean
     */
    public function existsByMedicoId(int $id): bool
    {
        return $this->table->exists(['medico_id' => $id]);
    }

    /**
     * Conta quantos registros de atendimentos existem com base no medico, data de atendimento e status agendado
     *
     * @param integer $id
     * @param Date $dataAtendimento
     * @return integer
     */
    public function countByMedicoIdAndDataAtendimento(int $id, Date $dataAtendimento): int
    {

        return $this->table
            ->find()
            ->where([
                'medico_id' => $id,
                'data_atendimento' =>  $dataAtendimento,
                'status' => 1
            ])
            ->count();
    }

    /**
     * Seta os parametros de paginação
     * Utilizado em findAll()
     *
     * @param array $paginate
     * @return self
     */
    public function paginate(array $paginate): self
    {
        $this->paginate = $paginate;
        return $this;
    }

    /**
     * Seta os parametros de filtro que vem via queryParams
     * Utilizado em findAll()
     *
     * @param array $filters
     * @return self
     */
    public function filters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }
}
