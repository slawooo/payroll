<?php declare(strict_types=1);

namespace App\Payroll\Application\PayrollReport;

use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;

class PayrollRowMapper
{
    public function map(Employee $employee): PayrollRowDto
    {
        return new PayrollRowDto(
            $employee->getName(),
            $employee->getSurname(),
            $employee->getDepartment()->getName(),
            $employee->getBaseSalary()->toString(),
            $employee->getBonus()->toString(),
            $this->formatBonusType($employee->getDepartment()->getBonusType()),
            $employee->getTotalSalary()->toString(),
        );
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
