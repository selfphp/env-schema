<?php

namespace Selfphp\EnvSchema\Exception;

class MissingRequiredVariableException extends EnvSchemaException
{
    public function __construct(string $variable)
    {
        parent::__construct("Missing required variable: $variable");
    }
}
