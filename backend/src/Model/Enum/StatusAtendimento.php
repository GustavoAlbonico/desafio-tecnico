<?php
declare(strict_types=1);

namespace App\Model\Enum;

use Cake\Database\Type\EnumLabelInterface;
use Cake\Utility\Inflector;

/**
 * StatusAtendimento Enum
 */
enum StatusAtendimento: int implements EnumLabelInterface
{

    case Agendado = 1;
    case Concluido = 2;
    case Cancelado = 3;
    
    /**
     * @return string
     */
    public function label(): string
    {
        return Inflector::humanize(Inflector::underscore($this->name));
    }
}
