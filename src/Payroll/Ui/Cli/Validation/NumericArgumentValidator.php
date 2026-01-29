<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli\Validation;

use InvalidArgumentException;

class NumericArgumentValidator implements CliValidator
{
    private const ERROR_MESSAGE = '%s must be numeric';

    public function validate(mixed $value, ?string $name = null): void
    {
        if (!is_numeric($value)) {
            $label = $name ?? 'Value';
            throw new InvalidArgumentException(sprintf(self::ERROR_MESSAGE, $label));
        }
    }
}
