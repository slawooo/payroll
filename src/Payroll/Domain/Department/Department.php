<?php declare(strict_types=1);

namespace App\Payroll\Domain\Department;

use App\Payroll\Domain\Department\Bonus\BonusCalculatorFactory;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Shared\Value\Money;

class Department
{
    private ?int $id;

    public function __construct(
        private string $name,
        private BonusType $bonusType,
        private float $bonusRate,
        private ?int $bonusYearsLimit = null
    ) {
    }

    public function calculateBonus(Money $baseSalary, int $yearsOfWork): Money
    {
        $bonusCalculatorFactory = new BonusCalculatorFactory();

        return $bonusCalculatorFactory->create($this->bonusType)->calculate(
            $baseSalary,
            $yearsOfWork,
            $this->bonusRate,
            $this->bonusYearsLimit,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBonusType(): BonusType
    {
        return $this->bonusType;
    }
}
