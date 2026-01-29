<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli\Validation;

use DateTimeImmutable;
use InvalidArgumentException;

class DateArgumentValidator implements CliValidator
{
    private const ERROR_MESSAGE = '%s must be a valid date in format YYYY-MM-DD';

    public function validate(mixed $value, ?string $name = null): void
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);

        // smart way for validate including edge cases (e.g. 2026-02-29 becomes 2026-03-01)
        if ($date === false || $date->format('Y-m-d') !== $value) {
            $label = $name ?? 'Value';
            throw new InvalidArgumentException(sprintf(self::ERROR_MESSAGE, $label));
        }
    }
}
