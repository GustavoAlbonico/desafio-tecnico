<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\Date;
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
        ->maxLength('nome', 255, 'O nome deve ter no máximo 255 caracteres.')
        ->requirePresence('nome', 'create', 'O nome é obrigatório.')
        ->notEmptyString('nome', 'O nome é obrigatório.');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 14, 'O CPF deve ter no máximo 14 caracteres. (com mascara)')
            ->minLength('cpf', 14, 'O CPF deve ter no minimo 14 caracteres. (com mascara)')
            ->add('cpf', 'formato', [
                'rule' => ['custom', '/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
                'message' => 'O CPF deve estar no formato 000.000.000-00.'
            ])
            ->requirePresence('cpf', 'create', 'O CPF é obrigatório.')
            ->notEmptyString('cpf', 'O CPF é obrigatório.')
            ->add('cpf', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'CPF já cadastrado.'
            ])
            ->add('cpf', 'valido', [
                'rule' => [$this, 'validarCpf'],
                'message' => 'CPF inválido.'
            ]);

        $validator
            ->date('data_nascimento', message: 'Data de nascimento inválida.')
            ->requirePresence('data_nascimento', 'create', 'A data de nascimento é obrigatória.')
            ->notEmptyDate('data_nascimento', 'A data de nascimento é obrigatória.')
            ->add('data_nascimento', 'dataValida', [
                'rule' => [$this, 'validarDataNascimento'],
                'message' => 'A data de nascimento não pode ser uma data futura.'
            ]);

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 45, 'O telefone deve ter no máximo 45 caracteres.')
            ->requirePresence('telefone', 'create', 'O telefone é obrigatório.')
            ->notEmptyString('telefone', 'O telefone é obrigatório.');

        $validator
            ->email('email', false, 'E-mail inválido.')
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

    public function validarCpf(string $value): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cpf) !== 11) {
            return false;
        }

        // Rejeita CPFs com todos os dígitos iguais (ex: 111.111.111-11)
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($c = 0; $c < $t; $c++) {
                $sum += (int) $cpf[$c] * (($t + 1) - $c);
            }
            $digit = ((10 * $sum) % 11) % 10;

            if ((int) $cpf[$t] !== $digit) {
                return false;
            }
        }

        return true;
    }

    public function validarDataNascimento(string $value): bool
    {
        $data = Date::parse($value);
        $hoje = Date::now();

        return $data->greaterThan($hoje);
    }
}
