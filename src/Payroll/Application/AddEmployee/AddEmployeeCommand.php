<?php declare(strict_types=1);

namespace App\Payroll\Application\AddEmployee;

class AddEmployeeCommand
{
    public function __construct(
        private readonly string $department,
        private readonly string $name,
        private readonly string $surname,
        private readonly int $yearsOfWork,
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

    public function getYearsOfWork(): int
    {
        return $this->yearsOfWork;
    }

    public function getBaseSalary(): float
    {
        return $this->baseSalary;
    }
}
