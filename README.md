# env-schema

**env-schema** is a lightweight PHP library for validating `.env` files using a declarative schema.

## ✅ Features
- Validate environment variables at runtime
- Supports types: `string`, `int`, `bool`, `float`
- Optional fields with `default`
- Required fields with `required`
- Allowed values (`allowed`) and regex (`pattern`) checks
- Fully tested (PHPUnit 12)
- No dependencies

## 📦 Installation

```bash
composer require selfphp/env-schema
```

## 🧪 Usage

### .env

```
APP_ENV=production
DEBUG=true
PORT=8080
APP_SECRET=abcd1234efgh5678ijkl9012mnop3456
```

### schema.php

```php
use Selfphp\EnvSchema\EnvSchema;

$schema = [
    'APP_ENV' => [
        'type' => 'string',
        'default' => 'production',
        'allowed' => ['dev', 'test', 'production'],
    ],
    'DEBUG' => [
        'type' => 'bool',
        'default' => false,
    ],
    'PORT' => [
        'type' => 'int',
        'required' => true,
    ],
    'APP_SECRET' => [
        'type' => 'string',
        'pattern' => '/^[A-Za-z0-9]{32}$/',
        'required' => true,
    ],
];

$validatedEnv = EnvSchema::validate($schema, __DIR__ . '/.env');

echo $validatedEnv['PORT']; // 8080
```

## 📁 Example Files

- `.env` – runtime environment
- `.env.example` – template for others
- `.gitignore` – excludes secrets

## 📄 License

MIT License © Damir Enseleit
