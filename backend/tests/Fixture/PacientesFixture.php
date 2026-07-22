<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class PacientesFixture extends TestFixture
{
    public string $table = 'pacientes';

    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'nome' => 'João da Silva',
                'cpf' => '000.000.000-00',
                'data_nascimento' => '1989-08-31',
                'telefone' => '(13) 98012-0987',
            ],
            [
                'id' => 2,
                'nome' => 'Maria Oliveira',
                'cpf' => '111.111.111-11',
                'data_nascimento' => '1989-08-31',
                'telefone' => '(13) 98012-0987',
                'email' => 'vanessa.prado@ficticio.com'
            ],
        ];

        parent::init();
    }
}