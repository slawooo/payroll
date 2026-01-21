<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Department\Bonus\BonusCalculatorFactory;
use App\Payroll\Domain\Department\Bonus\FixedBonusCalculator;
use App\Payroll\Domain\Department\Bonus\PercentageBonusCalculator;
use App\Payroll\Domain\Department\Value\BonusType;
use PHPUnit\Framework\TestCase;

final class BonusCalculatorFactoryTest extends TestCase
{
    public function testCreatesFixedBonusCalculatorForFixedType(): void
    {
        // Given
        $factory = new BonusCalculatorFactory();
        $type = BonusType::create(BonusType::FIXED);

        // When
        $calculator = $factory->create($type);

        // Then
        $this->assertInstanceOf(FixedBonusCalculator::class, $calculator);
    }

    public function testCreatesPercentageBonusCalculatorForPercentageType(): void
    {
        // Given
        $factory = new BonusCalculatorFactory();
        $type = BonusType::create(BonusType::PERCENTAGE);

        // When
        $calculator = $factory->create($type);

        // Then
        $this->assertInstanceOf(PercentageBonusCalculator::class, $calculator);
    }
}
