<?php declare(strict_types=1);

namespace App\Payroll\Application\PayrollReport;

use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Shared\Value\Money;

class PayrollRowMapper
{
    public function map(Employee $employee): PayrollRowDto
    {
        return new PayrollRowDto(
            $employee->getName(),
            $employee->getSurname(),
            $employee->getDepartment()->getName(),
            $this->formatMoney($employee->getBaseSalary()),
            $this->formatMoney($employee->getBonus()),
            $this->formatBonusType($employee->getDepartment()->getBonusType()),
            $this->formatMoney($employee->getTotalSalary()),
        );
    }

    private function formatMoney(Money $money): string
    {
        return '$' . number_format($money->getAmountInCents() / 100, 2);
    }

    private function formatBonusType(BonusType $bonusType): string
    {
        if ($bonusType->isFixed()) {
            return 'fixed';
        }

        if ($bonusType->isPercentage()) {
            return 'percentage';
        }

        return '';
    }
}
