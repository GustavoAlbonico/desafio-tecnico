<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Atendimento Entity
 *
 * @property int $id
 * @property \Cake\I18n\Date $data_atendimento
 * @property string $valor_consulta
 * @property int $status
 * @property int $paciente_id
 * @property int $medico_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Paciente $paciente
 * @property \App\Model\Entity\Medico $medico
 */
class Atendimento extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'data_atendimento' => true,
        'valor_consulta' => true,
        'status' => true,
        'paciente_id' => true,
        'medico_id' => true,
        'created' => true,
        'modified' => true,
        'paciente' => true,
        'medico' => true,
    ];
}
