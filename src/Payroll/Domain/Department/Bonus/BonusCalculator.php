<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Shared\Value\Money;

abstract class BonusCalculator
{
    abstract public function calculate(
        Money $baseSalary,
        int $yearsOfWork,
        float $bonusRate,
        ?int $bonusYearsLimit = null,
    ): Money;

    protected function getYearsOfBonus(int $yearsOfWork, ?int $bonusYearsLimit = null): int
    {
        if ($bonusYearsLimit === null) {
            return $yearsOfWork;
        }

        return min($yearsOfWork, $bonusYearsLimit);
    }
}
