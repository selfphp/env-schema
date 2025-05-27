<?php

namespace Selfphp\EnvSchema\Exception;

class PatternMismatchException extends EnvSchemaException
{
    public function __construct(string $variable, string $pattern)
    {
        parent::__construct("Value for '$variable' does not match pattern: $pattern");
    }
}
