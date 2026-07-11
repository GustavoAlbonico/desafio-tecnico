<?php
declare(strict_types=1);

use Migrations\BaseSeed;

class MedicosSeed extends BaseSeed
{
    public function run(): void
    {
        $json = file_get_contents(CONFIG . 'seeds/data/medicos.json');
        $data = json_decode($json, true);

        $this->execute("DELETE FROM medicos");

        $table = $this->table('medicos');
        $table->insert($data)->save();
    }
}
