<?php
declare(strict_types=1);

use Migrations\BaseSeed;

class PacientesSeed extends BaseSeed
{
    public function run(): void
    {
        $json = file_get_contents(CONFIG . 'Seeds/data/pacientes.json');
        $data = json_decode($json, true);

        $exists = $this->fetchRow('SELECT 1 FROM pacientes LIMIT 1');
        if($exists) return;

        $table = $this->table('pacientes');
        $table->insert($data)->save();
    }
}
