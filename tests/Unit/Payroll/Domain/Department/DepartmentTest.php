<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Department;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Shared\Value\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DepartmentTest extends TestCase
{
    #[DataProvider('bonusCasesProvider')]
    public function testCalculateBonus(
        string $bonusType,
        float $bonusRate,
        ?int $bonusYearsLimit,
        float $baseSalary,
        int $yearsOfWork,
        int $expectedBonusInCents,
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
        $bonus = $department->calculateBonus($base, $yearsOfWork);

        // Then
        $this->assertSame($expectedBonusInCents, $bonus->getAmountInCents());
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
            ],
            'percentage, exceeds limit' => [
                'bonusType' => BonusType::PERCENTAGE,
                'bonusRate' => 10.0,
                'bonusYearsLimit' => 2,
                'baseSalary' => 10000.00,
                'yearsOfWork' => 4,
                'expectedBonusInCents' => 210000,
            ],
        ];
    }
}
