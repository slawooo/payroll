<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Ui\Cli\Validation;

use App\Payroll\Ui\Cli\Validation\DateArgumentValidator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateArgumentValidatorTest extends TestCase
{
    private DateArgumentValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new DateArgumentValidator();
    }

    #[DataProvider('validDatesProvider')]
    public function testValidateAcceptsValidDates(string $date): void
    {
        $this->validator->validate($date);
        $this->addToAssertionCount(1);
    }

    public static function validDatesProvider(): array
    {
        return [
            'normal date' => ['2023-02-28'],
            'leap day' => ['2024-02-29'],
            'start of year' => ['2023-01-01'],
            'end of year' => ['2023-12-31'],
        ];
    }

    #[DataProvider('invalidDatesProvider')]
    public function testValidateRejectsInvalidDates(string $date): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be a valid date in format YYYY-MM-DD');

        $this->validator->validate($date);
    }

    public static function invalidDatesProvider(): array
    {
        return [
            'impossible day non-leap year' => ['2023-02-29'],
            'impossible day any year' => ['2023-02-30'],
            'invalid month' => ['2023-13-01'],
            'invalid day zero' => ['2023-01-00'],
            'wrong separator' => ['2023/02/01'],
            'garbage' => ['not-a-date'],
            'too short' => ['2023-1-1'],
        ];
    }
}
