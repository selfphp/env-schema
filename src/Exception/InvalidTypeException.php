<?php

namespace Selfphp\EnvSchema\Exception;

class InvalidTypeException extends EnvSchemaException
{
    public function __construct(string $variable, string $expected)
    {
        parent::__construct("Invalid type for '$variable': expected $expected");
    }
}
