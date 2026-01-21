<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Application\PayrollReport;

use App\Payroll\Application\PayrollReport\PayrollRowMapper;
use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Shared\Value\Money;
use PHPUnit\Framework\TestCase;

final class PayrollRowMapperTest extends TestCase
{
    public function testMapEmployeeToPayrollDto(): void
    {
        // Given
        $employee = $this->createMock(Employee::class);
        $department = $this->createMock(Department::class);
        $baseSalary = $this->createMock(Money::class);
        $bonus = $this->createMock(Money::class);
        $totalSalary = $this->createMock(Money::class);
        $bonusType = $this->createMock(BonusType::class);

        $employee->method('getName')->willReturn('Alice');
        $employee->method('getSurname')->willReturn('Smith');
        $employee->method('getDepartment')->willReturn($department);
        $employee->method('getBaseSalary')->willReturn($baseSalary);
        $employee->method('getBonus')->willReturn($bonus);
        $employee->method('getTotalSalary')->willReturn($totalSalary);

        $department->method('getName')->willReturn('HR');
        $department->method('getBonusType')->willReturn($bonusType);

        $baseSalary->method('toString')->willReturn('$8,000.00');
        $bonus->method('toString')->willReturn('$500.00');
        $totalSalary->method('toString')->willReturn('$8,500.00');

        $bonusType->method('isFixed')->willReturn(true);
        $bonusType->method('isPercentage')->willReturn(false);

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
