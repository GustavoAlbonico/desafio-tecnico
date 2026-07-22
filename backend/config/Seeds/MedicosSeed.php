<?php
declare(strict_types=1);

use Migrations\BaseSeed;

class MedicosSeed extends BaseSeed
{
    public function run(): void
    {
        $json = file_get_contents(CONFIG . 'Seeds/data/medicos.json');
        $data = json_decode($json, true);

        $exists = $this->fetchRow('SELECT 1 FROM medicos LIMIT 1');
        if($exists) return;

        $table = $this->table('medicos');
        $table->insert($data)->save();
    }
}
