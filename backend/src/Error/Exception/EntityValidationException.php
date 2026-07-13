<?php 

namespace App\Error\Exception;

use Cake\Http\Exception\HttpException;

class EntityValidationException extends HttpException  {

    private mixed $errors;

    public function __construct(
        string $message = "Erro de validação",
        mixed $errors = null
    ) {
        $this->errors = $errors;
        parent::__construct($message, 422);
    }

    public function getErrors():mixed {
        return $this->errors;
    }
}