<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department;

interface DepartmentRepository
{
    public function save(Department $department): void;

    public function flush(): void;
}
