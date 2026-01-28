<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Department\Bonus\PercentageBonusCalculator;
use App\Payroll\Domain\Shared\Value\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PercentageBonusCalculatorTest extends TestCase
{
    #[DataProvider('bonusCasesProvider')]
    public function testCalculatesBonus(
        float $baseSalary,
        int $yearsOfWork,
        float $bonusRate,
        ?int $bonusYearsLimit,
        int $expectedBonusInCents,
    ): void {
        // Given
        $calculator = new PercentageBonusCalculator();
        $base = Money::create($baseSalary);

        // When
        $bonus = $calculator->calculate(
            $base,
            $yearsOfWork,
            $bonusRate,
            $bonusYearsLimit,
        );

        // Then
        $this->assertSame($expectedBonusInCents, $bonus->getAmountInCents());
    }

    public static function bonusCasesProvider(): array
    {
        return [
            'within limit' => [
                10000.00,
                2,
                10.0,
                5,
                210000,
            ],
            'exceeds limit' => [
                10000.00,
                5,
                10.0,
                3,
                331000,
            ],
            'no limit' => [
                10000.00,
                4,
                5.0,
                null,
                215506,
            ],
        ];
    }
}
