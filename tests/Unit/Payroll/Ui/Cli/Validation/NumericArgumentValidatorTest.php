<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Ui\Cli\Validation;

use App\Payroll\Ui\Cli\Validation\NumericArgumentValidator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NumericArgumentValidatorTest extends TestCase
{
    private NumericArgumentValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new NumericArgumentValidator();
    }

    #[DataProvider('validNumericValuesProvider')]
    public function testValidateAcceptsNumericValues(mixed $value): void
    {
        $this->validator->validate($value);
        $this->addToAssertionCount(1);
    }

    public static function validNumericValuesProvider(): array
    {
        return [
            'integer' => [100],
            'float' => [100.5],
            'numeric string' => ['123.45'],
            'zero' => ['0'],
            'negative' => ['-10'],
        ];
    }

    #[DataProvider('invalidNumericValuesProvider')]
    public function testValidateThrowsExceptionForNonNumericValues(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be numeric');

        $this->validator->validate($value);
    }

    public static function invalidNumericValuesProvider(): array
    {
        return [
            'string' => ['not-a-number'],
            'null' => [null],
            'array' => [[123]],
            'object' => [new \stdClass()],
            'bool true' => [true],
            'bool false' => [false],
            'empty string' => [''],
        ];
    }
}
