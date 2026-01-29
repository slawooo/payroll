<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Shared\Value\Money;

class FixedBonusCalculator extends BonusCalculator
{
    public function calculate(
        Money $baseSalary,
        int $yearsOfWork,
        float $bonusRate,
        ?int $bonusYearsLimit = null,
    ): Money {
        $yearsOfBonus = $this->getYearsOfBonus($yearsOfWork, $bonusYearsLimit);
        $yearIncrement = Money::create($bonusRate);
        $bonus = Money::create(0);

        for ($year = 1; $year <= $yearsOfBonus; $year++) {
            $bonus = $bonus->add($yearIncrement);
        }

        return $bonus;
    }
}
