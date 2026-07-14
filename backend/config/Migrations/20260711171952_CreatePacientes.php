<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePacientes extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('pacientes');

        $table
        ->addColumn('nome','string',[
            'limit' => 255,
            'null' => false
        ])
        ->addColumn('cpf','string', [
            'limit' => 14,
            'null' => false
        ])
        ->addColumn('data_nascimento','date', [
            'null' => false
        ])
        ->addColumn('telefone','string',[
            'limit' => 45,
            'null' => false
        ])
        ->addColumn('email','string',[
            'limit' => 255,
            'null' => true,
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
        ->addIndex(['cpf'],['unique' => true])
        ->create();
    }
}
