<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class AtendimentosFixture extends TestFixture
{
    public string $table = 'atendimentos';

    public function init(): void
    {
        $records = [
            // ID 1: Atendimento normal (Agendado) para hoje
            [
                'id' => 1,
                'medico_id' => 1,
                'paciente_id' => 1,
                'data_atendimento' => date('Y-m-d'),
                'valor_consulta' => 150.00,
                'status' => 1,
            ],
            // ID 2: Atendimento já Concluído (usado para testar erro de alteração de status)
            [
                'id' => 2,
                'medico_id' => 1,
                'paciente_id' => 1,
                'data_atendimento' => date('Y-m-d'),
                'valor_consulta' => 200.00,
                'status' => 2,
            ],
        ];

        // Gera 16 agendamentos na data '2026-10-10' para o medico_id 1
        // Isso simula o cenário de limite diário (DAILY_DOCTOR_APPOINTMENT_LIMIT = 15)
        for ($i = 3; $i <= 18; $i++) {
            $records[] = [
                'id' => $i,
                'medico_id' => 1,
                'paciente_id' => 1,
                'data_atendimento' => '2026-10-10',
                'valor_consulta' => 100.00,
                'status' => 1,
            ];
        }

        $this->records = $records;

        parent::init();
    }
}