<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Shared\Value\Money;

class ZeroBonusCalculator extends BonusCalculator
{
    public function calculate(
        Money $baseSalary,
        int $yearsOfWork,
        float $bonusRate,
        ?int $bonusYearsLimit = null,
    ): Money {
        return Money::create(0);
    }
}
