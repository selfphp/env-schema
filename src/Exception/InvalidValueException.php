<?php

namespace Selfphp\EnvSchema\Exception;

class InvalidValueException extends EnvSchemaException
{
    public function __construct(string $variable, string $actual)
    {
        parent::__construct("Invalid value for '$variable': $actual");
    }
}
