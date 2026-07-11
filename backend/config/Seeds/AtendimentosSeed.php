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
        $json = file_get_contents(CONFIG . 'seeds/data/atendimentos.json');
        $data = json_decode($json, true);

        $this->execute("DELETE FROM atendimentos");

        $table = $this->table('atendimentos');
        $table->insert($data)->save();
    }
}
