<?php declare(strict_types=1);

namespace App\Payroll\Application\PayrollReport;

class PayrollRowDto
{
    public function __construct(
        private readonly string $name,
        private readonly string $surname,
        private readonly string $department,
        private readonly string $baseSalary,
        private readonly string $bonus,
        private readonly string $bonusType,
        private readonly string $totalSalary,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getBaseSalary(): string
    {
        return $this->baseSalary;
    }

    public function getBonus(): string
    {
        return $this->bonus;
    }

    public function getBonusType(): string
    {
        return $this->bonusType;
    }

    public function getTotalSalary(): string
    {
        return $this->totalSalary;
    }
}
