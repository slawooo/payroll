<?php declare(strict_types=1);

namespace App\Payroll\Domain\Employee;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Shared\Value\Money;

class Employee
{
    private ?int $id;

    private Money $bonus;

    private Money $totalSalary;

    public function __construct(
        private string $name,
        private string $surname,
        private Department $department,
        private int $yearsOfWork,
        private Money $baseSalary,
    ) {
        $this->applyBonus();
    }

    private function applyBonus(): void
    {
        $this->bonus = $this->department->calculateBonus($this->baseSalary, $this->yearsOfWork);
        $this->totalSalary = $this->baseSalary->add($this->bonus);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function getBaseSalary(): Money
    {
        return $this->baseSalary;
    }

    public function getBonus(): Money
    {
        return $this->bonus;
    }

    public function getTotalSalary(): Money
    {
        return $this->totalSalary;
    }
}
