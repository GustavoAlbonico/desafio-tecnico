<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pacientes Model
 *
 * @property \App\Model\Table\AtendimentosTable&\Cake\ORM\Association\HasMany $Atendimentos
 *
 * @method \App\Model\Entity\Paciente newEmptyEntity()
 * @method \App\Model\Entity\Paciente newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Paciente> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Paciente get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Paciente findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Paciente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Paciente> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Paciente|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Paciente saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Paciente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Paciente>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Paciente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Paciente> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Paciente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Paciente>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Paciente>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Paciente> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PacientesTable extends Table
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

        $this->setTable('pacientes');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Atendimentos', [
            'foreignKey' => 'paciente_id',
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
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 14)
            ->requirePresence('cpf', 'create')
            ->notEmptyString('cpf')
            ->add('cpf', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->date('data_nascimento')
            ->requirePresence('data_nascimento', 'create')
            ->notEmptyDate('data_nascimento');

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 45)
            ->requirePresence('telefone', 'create')
            ->notEmptyString('telefone');

        $validator
            ->email('email')
            ->allowEmptyString('email');

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
        $rules->add($rules->isUnique(['cpf']), ['errorField' => 'cpf']);

        return $rules;
    }
}
