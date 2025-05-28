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

MIT License ©2025 SELFPHP - Damir Enseleit
---

## 🖥️ Run CLI Example

You can quickly test your `.env` file and schema using the provided example script:

```bash
php examples/validate-env.php
```

This script:

- Loads a predefined schema
- Parses your `.env` file
- Outputs all validated values or detailed error messages

### Example Output

```
✅ .env file is valid.
APP_ENV = 'production'
DEBUG = true
PORT = 8080
APP_SECRET = 'abcd1234efgh5678ijkl9012mnop3456'
```

If validation fails, you'll see helpful errors like:

```
❌ Validation error: Missing required variable: PORT
```

> ℹ️ Make sure to install dependencies first via `composer install`.