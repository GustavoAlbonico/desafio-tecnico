<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class MedicosFixture extends TestFixture
{
    /**
     * Define o nome da tabela (opcional se o nome do arquivo seguir a convenção)
     */
    public string $table = 'medicos';

    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'nome' => 'Dr. Gregory House',
                'crm' => '12345-SC',
                'especialidade' => 'Otorrinolaringologia'
            ],
            [
                'id' => 2,
                'nome' => 'Dra. Meredith Grey',
                'crm' => '67890-SC',
                'especialidade' => 'Cirurgia Geral'
            ],
        ];

        parent::init();
    }
}