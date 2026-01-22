<?php declare(strict_types=1);

namespace App\Payroll\Application\AddEmployee;

class AddEmployeeCommand
{
    public function __construct(
        private readonly string $department,
        private readonly string $name,
        private readonly string $surname,
        private readonly string $hireDate,
        private readonly float $baseSalary,
    ){
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getHireDate(): string
    {
        return $this->hireDate;
    }

    public function getBaseSalary(): float
    {
        return $this->baseSalary;
    }
}
