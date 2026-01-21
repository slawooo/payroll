<?php declare(strict_types=1);

namespace App\Payroll\Infrastructure\Doctrine\Repository;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\DepartmentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineDepartmentRepository extends ServiceEntityRepository implements DepartmentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function save(Department $department): void
    {
        $this->getEntityManager()->persist($department);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
