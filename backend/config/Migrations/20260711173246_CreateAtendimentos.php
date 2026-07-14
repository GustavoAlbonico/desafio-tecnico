<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAtendimentos extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('atendimentos');

        $table
        ->addColumn('data_atendimento','date',[
            'null' => false
        ])
        ->addColumn('valor_consulta','decimal',[
            'precision' => 12,
            'scale' => 2,
            'null' => false
        ])
        ->addColumn('status','integer', [
            'default' => 1,
            'null' => false
        ])
        ->addColumn('paciente_id','integer',[
            'null' => false
        ])
        ->addColumn('medico_id','integer',[
            'null' => false
        ])
         ->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])
        ->addColumn('modified', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'update' => 'CURRENT_TIMESTAMP',
            'null' => false
        ])
        ->addForeignKey(
            'paciente_id',
            'pacientes',
            'id',
            [
                'delete' => 'NO_ACTION',
                'update' => 'NO_ACTION'
            ]
        )
        ->addForeignKey(
            'medico_id',
            'medicos',
            'id',
            [
                'delete' => 'NO_ACTION',
                'update' => 'NO_ACTION'
            ]
        )
        ->create();
    }
}
