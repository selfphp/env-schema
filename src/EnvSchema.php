<?php

namespace Selfphp\EnvSchema;

use Selfphp\EnvSchema\Exception\MissingRequiredVariableException;
use Selfphp\EnvSchema\Exception\InvalidTypeException;
use Selfphp\EnvSchema\Exception\InvalidValueException;
use Selfphp\EnvSchema\Exception\PatternMismatchException;

/**
 * EnvSchema provides validation for .env files based on a declarative schema definition.
 */
class EnvSchema
{
    /**
     * Validates environment variables against a given schema.
     *
     * @param array<string, array<string, mixed>> $schema  Associative array of schema definitions per environment key.
     * @param string $envPath Path to the .env file (default: '.env').
     * @return array<string, mixed> Validated and type-casted environment values.
     */
    public static function validate(array $schema, string $envPath = '.env'): array
    {
        $env = self::loadEnvFile($envPath);
        $validated = [];

        foreach ($schema as $key => $rules) {
            $validated[$key] = self::validateVariable($key, $rules, $env);
        }

        return $validated;
    }

    /**
     * Parses a .env file into an associative array.
     *
     * @param string $envPath
     * @return array<string, string> Parsed key-value pairs.
     */
    private static function loadEnvFile(string $envPath): array
    {
        if (!file_exists($envPath)) {
            throw new \InvalidArgumentException("Env file not found: $envPath");
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!is_array($lines)) {
            throw new \RuntimeException("Unable to read .env file: $envPath");
        }

        $env = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (
                $line === '' ||
                str_starts_with($line, '#') ||
                !str_contains($line, '=')
            ) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }

        return $env;
    }

    /**
     * Validates and casts a single environment variable based on schema rules.
     *
     * @param string $key    The name of the variable.
     * @param array<string, mixed> $rules   Schema definition for the variable.
     * @param array<string, string> $env    All loaded environment variables.
     * @return mixed         Validated and casted value.
     */
    private static function validateVariable(string $key, array $rules, array $env): mixed
    {
        $value = array_key_exists($key, $env) ? $env[$key] : ($rules['default'] ?? null);

        if (self::isMissingRequired($value, $rules)) {
            throw new MissingRequiredVariableException($key);
        }

        if (isset($rules['allowed']) && !in_array($value, $rules['allowed'], true)) {
            throw new InvalidValueException($key, (string) $value);
        }

        if (isset($rules['pattern']) && !preg_match($rules['pattern'], $value)) {
            throw new PatternMismatchException($key, $rules['pattern']);
        }

        if (isset($rules['type'])) {
            $value = self::castAndValidateType($value, $rules['type'], $key);
        }

        return $value;
    }

    /**
     * Determines if a required variable is missing.
     *
     * @param mixed $value
     * @param array<string, mixed> $rules
     * @return bool
     */
    private static function isMissingRequired(mixed $value, array $rules): bool
    {
        return ($value === null || $value === '') && ($rules['required'] ?? false);
    }

    /**
     * Casts and validates the type of a value.
     *
     * @param mixed $value
     * @param string $type
     * @param string $key
     * @return mixed
     *
     * @throws InvalidTypeException
     */
    private static function castAndValidateType(mixed $value, string $type, string $key): mixed
    {
        $casted = match ($type) {
            'int' => filter_var($value, FILTER_VALIDATE_INT),
            'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            'float' => filter_var($value, FILTER_VALIDATE_FLOAT),
            'string' => (string) $value,
            default => throw new InvalidTypeException($key, $type)
        };

        if ($casted === false && $type !== 'bool') {
            throw new InvalidTypeException($key, $type);
        }

        if ($casted === null && $type === 'bool') {
            throw new InvalidTypeException($key, $type);
        }

        return $casted;
    }
}
