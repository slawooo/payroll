<?php declare(strict_types=1);

namespace App\Payroll\Domain\Employee;

interface EmployeeRepository
{
    public function save(Employee $employee): void;

    public function flush(): void;
}
