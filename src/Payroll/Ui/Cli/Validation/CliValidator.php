<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli\Validation;

interface CliValidator
{
    public function validate(mixed $value, ?string $name = null): void;
}
