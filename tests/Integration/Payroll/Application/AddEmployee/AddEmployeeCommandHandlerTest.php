<?php declare(strict_types=1);

namespace App\Tests\Integration\Payroll\Application\AddEmployee;

use App\Payroll\Application\AddEmployee\AddEmployeeCommand;
use App\Payroll\Application\AddEmployee\AddEmployeeCommandHandler;
use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\EmployeeRepository;
use App\Tests\Integration\Integration;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

final class AddEmployeeCommandHandlerTest extends Integration
{
    private AddEmployeeCommandHandler $handler;

    private EmployeeRepository $employeeRepository;

    protected function setUp(): void
    {
        $this->handler = self::getContainer()->get(AddEmployeeCommandHandler::class);
        $this->employeeRepository = self::getContainer()->get(EmployeeRepository::class);
    }

    public function testAddsEmployeeToExistingDepartment(): void
    {
        // Given
        $command = new AddEmployeeCommand(
            department: 'IT',
            name: 'John',
            surname: 'Doe',
            hireDate: (new DateTimeImmutable())->modify('-3 years')->format('Y-m-d'),
            baseSalary: 10000.00,
        );

        $this->addDepartment();
        $this->assertEmpty($this->employeeRepository->findEmployeesBy());

        // When
        $this->handler->run($command);

        // Then
        $employees = $this->employeeRepository->findEmployeesBy(['name' => 'John', 'surname' => 'Doe']);
        $this->assertCount(1, $employees);

        $employee = $employees[0];
        $this->assertSame('John', $employee->getName());
        $this->assertSame('Doe', $employee->getSurname());
        $this->assertSame('IT', $employee->getDepartment()->getName());
        $this->assertSame('$10,000.00', $employee->getBaseSalary()->toString());
        $this->assertSame('$300.00', $employee->getBonus()->toString());
        $this->assertSame('$10,300.00', $employee->getTotalSalary()->toString());
    }

    private function addDepartment(): void
    {
        $department = new Department(
            name: 'IT',
            bonusType: BonusType::create(BonusType::FIXED),
            bonusRate: 100.0,
            bonusYearsLimit: 5,
        );

        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->persist($department);
        $em->flush();
    }

    public function testThrowsExceptionWhenDepartmentNotFound(): void
    {
        // Given
        $command = new AddEmployeeCommand(
            department: 'NonExisting',
            name: 'John',
            surname: 'Doe',
            hireDate: (new DateTimeImmutable())->modify('-3 years')->format('Y-m-d'),
            baseSalary: 10000.00,
        );

        // Then
        $this->expectException(DepartmentException::class);
        $this->expectExceptionMessage('Department NonExisting not found');

        // When
        $this->handler->run($command);
    }
}
