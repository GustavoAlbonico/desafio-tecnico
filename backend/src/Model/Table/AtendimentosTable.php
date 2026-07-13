<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Atendimentos Model
 *
 * @property \App\Model\Table\PacientesTable&\Cake\ORM\Association\BelongsTo $Pacientes
 * @property \App\Model\Table\MedicosTable&\Cake\ORM\Association\BelongsTo $Medicos
 *
 * @method \App\Model\Entity\Atendimento newEmptyEntity()
 * @method \App\Model\Entity\Atendimento newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Atendimento> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Atendimento get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Atendimento findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Atendimento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Atendimento> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Atendimento|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Atendimento saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Atendimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Atendimento>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Atendimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Atendimento> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Atendimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Atendimento>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Atendimento>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Atendimento> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AtendimentosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('atendimentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Pacientes', [
            'foreignKey' => 'paciente_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Medicos', [
            'foreignKey' => 'medico_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->date('data_atendimento', ['ymd'], 'A data do atendimento deve ser uma data válida.')
            ->requirePresence('data_atendimento', 'create', 'A data do atendimento é obrigatória.')
            ->notEmptyDate('data_atendimento', 'A data do atendimento é obrigatória.');

        $validator
            ->decimal('valor_consulta', null, 'O valor da consulta deve ser um número decimal válido.')
            ->requirePresence('valor_consulta', 'create', 'O valor da consulta é obrigatório.')
            ->notEmptyString('valor_consulta', 'O valor da consulta é obrigatório.');

        $validator
            ->integer('status', 'O status deve ser um número inteiro.')
            ->notEmptyString('status', 'O status é obrigatório.');

        $validator
            ->integer('paciente_id', 'O paciente informado é inválido.')
            ->requirePresence('paciente_id', 'create', 'O paciente é obrigatório.')
            ->notEmptyString('paciente_id', 'O paciente é obrigatório.');

        $validator
            ->integer('medico_id', 'O médico informado é inválido.')
            ->requirePresence('medico_id', 'create', 'O médico é obrigatório.')
            ->notEmptyString('medico_id', 'O médico é obrigatório.');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add(
            $rules->existsIn(['paciente_id'], 'Pacientes'),
            ['errorField' => 'paciente_id', 'message' => 'Paciente não encontrado.']
        );

        $rules->add(
            $rules->existsIn(['medico_id'], 'Medicos'),
            ['errorField' => 'medico_id', 'message' => 'Médico não encontrado.']
        );

        return $rules;
    }
}
