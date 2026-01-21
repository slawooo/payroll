<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department\Value;

use App\Payroll\Domain\Department\Exception\DepartmentException;

class BonusType
{
    public const FIXED = 'fixed';
    public const PERCENTAGE = 'percentage';

    private function __construct(
        private readonly string $value,
    ) {
    }

    /**
     * @throws DepartmentException
     */
    public static function create(string $value): static
    {
        return match ($value) {
            self::FIXED => new static(self::FIXED),
            self::PERCENTAGE => new static(self::PERCENTAGE),
            default => throw new DepartmentException(
                sprintf('Unknown bonus type "%s"', $value)
            ),
        };
    }

    public function isFixed(): bool
    {
        return ($this->value === static::FIXED);
    }

    public function isPercentage(): bool
    {
        return ($this->value === static::PERCENTAGE);
    }
}
