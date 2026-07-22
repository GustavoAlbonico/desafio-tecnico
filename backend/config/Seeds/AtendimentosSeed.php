<?php
declare(strict_types=1);

use Migrations\BaseSeed;

class AtendimentosSeed extends BaseSeed
{

    public function getDependencies(): array
    {
        return [
            'MedicosSeed',
            'PacientesSeed'
        ];
    }

    public function run(): void
    {
        $json = file_get_contents(CONFIG . 'Seeds/data/atendimentos.json');
        $data = json_decode($json, true);

        $exists = $this->fetchRow('SELECT 1 FROM atendimentos LIMIT 1');
        if($exists) return;

        $table = $this->table('atendimentos');
        $table->insert($data)->save();
    }
}
