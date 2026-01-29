<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department;

interface DepartmentRepository
{
    public function save(Department $department): void;

    public function flush(): void;

    /**
     * @return Department|null
     */
    public function findOneBy(array $criteria, array|null $orderBy = null): object|null;
}
