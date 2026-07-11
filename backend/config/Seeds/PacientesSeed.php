<?php
declare(strict_types=1);

use Migrations\BaseSeed;

class PacientesSeed extends BaseSeed
{
    public function run(): void
    {
        $json = file_get_contents(CONFIG . 'seeds/data/pacientes.json');
        $data = json_decode($json, true);

        $this->execute("DELETE FROM pacientes");

        $table = $this->table('pacientes');
        $table->insert($data)->save();
    }
}
