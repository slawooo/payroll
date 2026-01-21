<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Department\Bonus\FixedBonusCalculator;
use App\Payroll\Domain\Shared\Value\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FixedBonusCalculatorTest extends TestCase
{
    #[DataProvider('bonusCasesProvider')]
    public function testCalculatesBonus(
        float $baseSalary,
        int $yearsOfWork,
        float $bonusRate,
        ?int $bonusYearsLimit,
        string $expectedBonus
    ): void {
        // Given
        $calculator = new FixedBonusCalculator();
        $base = Money::create($baseSalary);

        // When
        $bonus = $calculator->calculate(
            $base,
            $yearsOfWork,
            $bonusRate,
            $bonusYearsLimit,
        );

        // Then
        $this->assertSame($expectedBonus, $bonus->toString());
    }

    public static function bonusCasesProvider(): array
    {
        return [
            'within limit' => [
                10000.00,  // baseSalary
                3,         // yearsOfWork
                100.00,    // bonusRate
                5,         // bonusYearsLimit
                '$300.00', // expectedBonus
            ],
            'exceeds limit' => [
                10000.00,
                10,
                100.00,
                5,
                '$500.00',
            ],
            'no limit' => [
                10000.00,
                8,
                150.00,
                null,
                '$1,200.00',
            ],
        ];
    }
}
