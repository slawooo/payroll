<?php declare(strict_types=1);

namespace App\Payroll\Infrastructure\Doctrine\Repository;

use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Employee\EmployeeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineEmployeeRepository extends ServiceEntityRepository implements EmployeeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee): void
    {
        $this->getEntityManager()->persist($employee);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
