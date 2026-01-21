<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department\Bonus;

use App\Payroll\Domain\Department\Value\BonusType;

class BonusCalculatorFactory
{
    public function create(BonusType $type): BonusCalculator
    {
        if ($type->isFixed()) {
            return new FixedBonusCalculator();
        }

        if ($type->isPercentage()) {
            return new PercentageBonusCalculator();
        }

        return new ZeroBonusCalculator();
    }
}
