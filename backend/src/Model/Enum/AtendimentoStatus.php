<?php
declare(strict_types=1);

namespace App\Model\Enum;

use Cake\Database\Type\EnumLabelInterface;
use Cake\Utility\Inflector;

/**
 * AtendimentoStatus Enum
 */
enum AtendimentoStatus: int implements EnumLabelInterface
{

    case Agendado = 1;
    case Cancelado = 2;
    case Finalizado = 3;
    
    /**
     * @return string
     */
    public function label(): string
    {
        return Inflector::humanize(Inflector::underscore($this->name));
    }
}
