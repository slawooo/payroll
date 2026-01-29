<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Employee;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Shared\Value\Money;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EmployeeTest extends TestCase
{
    #[DataProvider('bonusCasesProvider')]
    public function testEmployeeBonusAndTotalSalary(
        string $bonusType,
        float $bonusRate,
        ?int $bonusYearsLimit,
        float $baseSalary,
        int $yearsOfWork,
        int $expectedBonusInCents,
        int $expectedTotalSalaryInCents,
    ): void {
        // Given
        $department = new Department(
            name: 'IT',
            bonusType: BonusType::create($bonusType),
            bonusRate: $bonusRate,
            bonusYearsLimit: $bonusYearsLimit,
        );
        $base = Money::create($baseSalary);
        $hireDate = (new DateTimeImmutable())->modify("-{$yearsOfWork} years");

        // When
        $employee = new Employee(
            name: 'John',
            surname: 'Doe',
            department: $department,
            hireDate: $hireDate,
            baseSalary: $base,
        );

        // Then
        $this->assertSame($expectedBonusInCents, $employee->getBonus()->getAmountInCents());
        $this->assertSame($expectedTotalSalaryInCents, $employee->getTotalSalary()->getAmountInCents());
    }

    public static function bonusCasesProvider(): array
    {
        return [
            'fixed, within limit' => [
                'bonusType' => BonusType::FIXED,
                'bonusRate' => 100.00,
                'bonusYearsLimit' => 5,
                'baseSalary' => 10000.00,
                'yearsOfWork' => 3,
                'expectedBonusInCents' => 30000,
                'expectedTotalSalaryInCents' => 1030000,
            ],
            'percentage, exceeds limit' => [
                'bonusType' => BonusType::PERCENTAGE,
                'bonusRate' => 10.0,
                'bonusYearsLimit' => 2,
                'baseSalary' => 10000.00,
                'yearsOfWork' => 4,
                'expectedBonusInCents' => 210000,
                'expectedTotalSalaryInCents' => 1210000,
            ],
        ];
    }
}
