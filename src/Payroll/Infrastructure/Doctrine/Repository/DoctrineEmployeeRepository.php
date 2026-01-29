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

    public function findEmployeesBy(array $filters = [], ?string $orderBy = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.department', 'd')
            ->addSelect('d');

        if (!empty($filters['name'])) {
            $qb->andWhere('e.name = :name')
                ->setParameter('name', $filters['name']);
        }

        if (!empty($filters['surname'])) {
            $qb->andWhere('e.surname = :surname')
                ->setParameter('surname', $filters['surname']);
        }

        if (!empty($filters['department'])) {
            $qb->andWhere('d.name = :departmentName')
                ->setParameter('departmentName', $filters['department']);
        }

        if ($orderBy !== null) {
            $allowedSorts = [
                'name' => 'e.name',
                'surname' => 'e.surname',
                'base' => 'e.baseSalary.amountInCents',
                'addition' => 'e.bonus.amountInCents',
                'total' => 'e.totalSalary.amountInCents',
                'bonusType' => 'd.bonusType.value',
                'department' => 'd.name',
            ];

            if (isset($allowedSorts[$orderBy])) {
                $qb->orderBy($allowedSorts[$orderBy], 'ASC');
            }
        }

        return $qb->getQuery()->getResult();
    }
}
