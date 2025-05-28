#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Selfphp\EnvSchema\EnvSchema;
use Selfphp\EnvSchema\Exception\EnvSchemaException;

$schema = [
    'APP_ENV' => [
        'type'    => 'string',
        'default' => 'production',
        'allowed' => ['dev', 'test', 'production'],
    ],
    'DEBUG' => [
        'type'    => 'bool',
        'default' => false,
    ],
    'PORT' => [
        'type'     => 'int',
        'required' => true,
    ],
    'APP_SECRET' => [
        'type'     => 'string',
        'pattern'  => '/^[A-Za-z0-9]{32}$/',
        'required' => true,
    ],
];

try {
    $env = EnvSchema::validate($schema, __DIR__ . '/../.env');
    echo "✅ .env file is valid.\n";
    foreach ($env as $key => $value) {
        echo "$key = " . var_export($value, true) . "\n";
    }
} catch (EnvSchemaException $e) {
    echo "❌ Validation error: " . $e->getMessage() . "\n";
    exit(1);
} catch (\Throwable $e) {
    echo "❌ General error: " . $e->getMessage() . "\n";
    exit(2);
}
