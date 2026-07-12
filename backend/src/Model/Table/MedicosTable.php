<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Medicos Model
 *
 * @property \App\Model\Table\AtendimentosTable&\Cake\ORM\Association\HasMany $Atendimentos
 *
 * @method \App\Model\Entity\Medico newEmptyEntity()
 * @method \App\Model\Entity\Medico newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Medico> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Medico get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Medico findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Medico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Medico> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Medico|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Medico saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Medico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Medico>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Medico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Medico> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Medico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Medico>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Medico>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Medico> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MedicosTable extends Table
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

        $this->setTable('medicos');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Atendimentos', [
            'foreignKey' => 'medico_id',
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
            ->maxLength('nome', 255, 'O nome deve ter no máximo 255 caracteres.')
            ->requirePresence('nome', 'create', 'O nome é obrigatório.')
            ->notEmptyString('nome', 'O nome é obrigatório.');

        $validator
            ->scalar('crm')
            ->maxLength('crm', 20, 'O CRM deve ter no máximo 20 caracteres.')
            ->requirePresence('crm', 'create', 'O CRM é obrigatório.')
            ->notEmptyString('crm', 'O CRM é obrigatório.')
            ->add('crm', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'CRM já cadastrado.'
            ]);

        $validator
            ->scalar('especialidade')
            ->maxLength('especialidade', 255, 'A especialidade deve ter no máximo 255 caracteres.')
            ->requirePresence('especialidade', 'create', 'A especialidade é obrigatória.')
            ->notEmptyString('especialidade', 'A especialidade é obrigatória.');

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
        $rules->add($rules->isUnique(['crm']), ['errorField' => 'crm']);

        return $rules;
    }
}
