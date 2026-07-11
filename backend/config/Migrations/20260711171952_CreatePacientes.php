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
        ->addTimestamps('created','modified')
        ->addIndex(['cpf'],['unique' => true])
        ->create();
    }
}
