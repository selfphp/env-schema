<?php

use PHPUnit\Framework\TestCase;
use Selfphp\EnvSchema\EnvSchema;
use Selfphp\EnvSchema\Exception\MissingRequiredVariableException;
use Selfphp\EnvSchema\Exception\InvalidValueException;
use Selfphp\EnvSchema\Exception\PatternMismatchException;
use Selfphp\EnvSchema\Exception\InvalidTypeException;

class EnvSchemaTest extends TestCase
{
    private string $envPath;

    protected function setUp(): void
    {
        $this->envPath = __DIR__ . '/test.env';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->envPath)) {
            unlink($this->envPath);
        }
    }

    private function writeEnv(string $content): void
    {
        file_put_contents($this->envPath, $content);
    }

    public function testValidEnvFileIsParsedCorrectly(): void
    {
        $this->writeEnv("APP_ENV=production\nPORT=8080\nDEBUG=true\nAPP_SECRET=abcd1234efgh5678ijkl9012mnop3456");

        $schema = [
            'APP_ENV' => ['type' => 'string', 'default' => 'dev', 'allowed' => ['production', 'dev']],
            'PORT' => ['type' => 'int', 'required' => true],
            'DEBUG' => ['type' => 'bool', 'default' => false],
            'APP_SECRET' => ['type' => 'string', 'pattern' => '/^[A-Za-z0-9]{32}$/', 'required' => true]
        ];

        $result = EnvSchema::validate($schema, $this->envPath);

        $this->assertEquals('production', $result['APP_ENV']);
        $this->assertEquals(8080, $result['PORT']);
        $this->assertTrue($result['DEBUG']);
    }

    public function testMissingRequiredThrowsException(): void
    {
        $this->writeEnv("APP_ENV=production\nPORT=8080");

        $schema = [
            'APP_SECRET' => ['type' => 'string', 'required' => true]
        ];

        $this->expectException(MissingRequiredVariableException::class);
        EnvSchema::validate($schema, $this->envPath);
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->writeEnv("MODE=invalid");

        $schema = [
            'MODE' => ['type' => 'string', 'allowed' => ['dev', 'prod']]
        ];

        $this->expectException(InvalidValueException::class);
        EnvSchema::validate($schema, $this->envPath);
    }

    public function testPatternMismatchThrowsException(): void
    {
        $this->writeEnv("APP_SECRET=invalidsecret");

        $schema = [
            'APP_SECRET' => ['type' => 'string', 'pattern' => '/^[A-Za-z0-9]{32}$/']
        ];

        $this->expectException(PatternMismatchException::class);
        EnvSchema::validate($schema, $this->envPath);
    }

    public function testInvalidTypeThrowsException(): void
    {
        $this->writeEnv("PORT=notanumber");

        $schema = [
            'PORT' => ['type' => 'int']
        ];

        $this->expectException(InvalidTypeException::class);
        EnvSchema::validate($schema, $this->envPath);
    }
}
