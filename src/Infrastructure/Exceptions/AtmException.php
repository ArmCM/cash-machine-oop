<?php

namespace App\Infrastructure\Exceptions;

class AtmException extends \Exception
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getPayMessage(): string
    {
        return "Error en ATM al pagar: " . $this->getMessage() . PHP_EOL;
    }
}
