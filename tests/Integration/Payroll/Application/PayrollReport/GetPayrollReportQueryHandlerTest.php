<?php declare(strict_types=1);

namespace App\Tests\Integration\Payroll\Application\PayrollReport;

use App\Payroll\Application\PayrollReport\GetPayrollReportQuery;
use App\Payroll\Application\PayrollReport\GetPayrollReportQueryHandler;
use App\Payroll\Application\PayrollReport\PayrollRowDto;
use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Shared\Value\Money;
use App\Tests\Integration\Integration;
use Doctrine\ORM\EntityManagerInterface;

final class GetPayrollReportQueryHandlerTest extends Integration
{
    private EntityManagerInterface $em;

    private GetPayrollReportQueryHandler $handler;

    protected function setUp(): void
    {
        $this->em = self::getContainer()->get(EntityManagerInterface::class);
        $this->handler = self::getContainer()->get(GetPayrollReportQueryHandler::class);
    }

    public function testReportForItDepartmentSortedBySurname(): void
    {
        // Given
        $hr = $this->createDepartment('HR', 200);
        $it = $this->createDepartment('IT', 300);

        $this->em->persist($it);
        $this->em->persist($hr);

        $johnIt = $this->createEmployee('John', 'Doe', $it, 10000);
        $annaIt = $this->createEmployee('Anna', 'Smith', $it, 9000);
        $gregHr = $this->createEmployee('Greg', 'Brown', $hr, 8000);

        $this->em->persist($johnIt);
        $this->em->persist($annaIt);
        $this->em->persist($gregHr);
        $this->em->flush();

        $query = new GetPayrollReportQuery(
            filters: ['department' => 'IT'],
            orderBy: 'surname',
        );

        // When
        $rows = $this->handler->run($query);

        // Then
        $this->assertCount(2, $rows);
        $this->assertContainsOnlyInstancesOf(PayrollRowDto::class, $rows);

        $row1 = $rows[0];
        $row2 = $rows[1];

        $this->assertSame('John', $row1->getName());
        $this->assertSame('Doe', $row1->getSurname());
        $this->assertSame('IT', $row1->getDepartment());
        $this->assertSame('$10,000.00', $row1->getBaseSalary());
        $this->assertSame('fixed', $row1->getBonusType());
        $this->assertSame('$600.00', $row1->getBonus());
        $this->assertSame('$10,600.00', $row1->getTotalSalary());

        $this->assertSame('Anna', $row2->getName());
        $this->assertSame('Smith', $row2->getSurname());
        $this->assertSame('IT', $row2->getDepartment());
        $this->assertSame('$9,000.00', $row2->getBaseSalary());
        $this->assertSame('fixed', $row2->getBonusType());
        $this->assertSame('$600.00', $row2->getBonus());
        $this->assertSame('$9,600.00', $row2->getTotalSalary());
    }

    private function createDepartment(string $name, float $bonusRate): Department
    {
        return new Department(
            name: $name,
            bonusType: BonusType::create(BonusType::FIXED),
            bonusRate: $bonusRate,
        );
    }

    private function createEmployee(string $name, string $surname, Department $department, float $salary): Employee
    {
        return new Employee(
            name: $name,
            surname: $surname,
            department: $department,
            yearsOfWork: 2,
            baseSalary: Money::create($salary),
        );
    }
}
