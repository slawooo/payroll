<?php declare(strict_types=1);

namespace App\Payroll\Application\AddDepartment;

class AddDepartmentCommand
{
    public function __construct(
        private readonly string $name,
        private readonly string $bonusType,
        private readonly float $bonusRate,
        private readonly ?int $bonusYearsLimit,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBonusType(): string
    {
        return $this->bonusType;
    }

    public function getBonusRate(): float
    {
        return $this->bonusRate;
    }

    public function getBonusYearsLimit(): ?int
    {
        return $this->bonusYearsLimit;
    }
}
