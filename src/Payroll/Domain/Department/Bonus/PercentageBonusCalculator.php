<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Shared\Value\Money;

class PercentageBonusCalculator extends BonusCalculator
{
    public function calculate(
        Money $baseSalary,
        int $yearsOfWork,
        float $bonusRate,
        ?int $bonusYearsLimit = null,
    ): Money {
        $yearsOfBonus = $this->getYearsOfBonus($yearsOfWork, $bonusYearsLimit);
        $totalSalary = clone $baseSalary;

        for ($year = 1; $year <= $yearsOfBonus; $year++) {
            $totalSalary = $totalSalary->increaseByPercent($bonusRate);
        }

        return $totalSalary->subtract($baseSalary);
    }
}
