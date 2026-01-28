<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Application\PayrollReport;

use App\Payroll\Application\PayrollReport\PayrollRowMapper;
use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Shared\Value\Money;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class PayrollRowMapperTest extends TestCase
{
    /**
     * @throws DepartmentException
     * @throws Exception
     */
    public function testMapEmployeeToPayrollDto(): void
    {
        // Given
        $baseSalary = Money::create('8000');
        $bonus = Money::create('500');
        $totalSalary = Money::create('8500');
        $bonusType = BonusType::create('fixed');
        $department = new Department('HR', $bonusType, 0);

        $employee = $this->createMock(Employee::class);
        $employee->method('getName')->willReturn('Alice');
        $employee->method('getSurname')->willReturn('Smith');
        $employee->method('getDepartment')->willReturn($department);
        $employee->method('getBaseSalary')->willReturn($baseSalary);
        $employee->method('getBonus')->willReturn($bonus);
        $employee->method('getTotalSalary')->willReturn($totalSalary);

        $mapper = new PayrollRowMapper();

        // When
        $dto = $mapper->map($employee);

        // Then
        $this->assertSame('Alice', $dto->getName());
        $this->assertSame('Smith', $dto->getSurname());
        $this->assertSame('HR', $dto->getDepartment());
        $this->assertSame('$8,000.00', $dto->getBaseSalary());
        $this->assertSame('$500.00', $dto->getBonus());
        $this->assertSame('fixed', $dto->getBonusType());
        $this->assertSame('$8,500.00', $dto->getTotalSalary());
    }
}
