<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Employee;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Shared\Value\Money;
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
        string $expectedBonus,
        string $expectedTotalSalary
    ): void {
        // Given
        $department = new Department(
            name: 'IT',
            bonusType: BonusType::create($bonusType),
            bonusRate: $bonusRate,
            bonusYearsLimit: $bonusYearsLimit,
        );
        $base = Money::create($baseSalary);

        // When
        $employee = new Employee(
            name: 'John',
            surname: 'Doe',
            department: $department,
            yearsOfWork: $yearsOfWork,
            baseSalary: $base,
        );

        // Then
        $this->assertSame($expectedBonus, $employee->getBonus()->toString());
        $this->assertSame($expectedTotalSalary, $employee->getTotalSalary()->toString());
    }

    public static function bonusCasesProvider(): array
    {
        return [
            'fixed, within limit' => [
                'bonusType'          => BonusType::FIXED,
                'bonusRate'          => 100.00,
                'bonusYearsLimit'    => 5,
                'baseSalary'         => 10000.00,
                'yearsOfWork'        => 3,
                'expectedBonus'      => '$300.00',
                'expectedTotalSalary'=> '$10,300.00',
            ],
            'percentage, exceeds limit' => [
                'bonusType'          => BonusType::PERCENTAGE,
                'bonusRate'          => 10.0,
                'bonusYearsLimit'    => 2,
                'baseSalary'         => 10000.00,
                'yearsOfWork'        => 4,
                'expectedBonus'      => '$2,100.00',
                'expectedTotalSalary'=> '$12,100.00',
            ],
        ];
    }
}
