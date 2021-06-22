<?php

namespace SIGEVframework\ConexaoBanco\Exception;

use PDOException;

class DatabaseConnectionException extends PDOException
{
    protected $message;

    protected $code;

    public function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}