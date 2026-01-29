<?php declare(strict_types=1);

namespace App\Payroll\Application\PayrollReport;

use App\Payroll\Domain\Employee\EmployeeRepository;

class GetPayrollReportQueryHandler
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly PayrollRowMapper $payrollRowMapper,
    ) {
    }

    /**
     * @return PayrollRowDto[]
     */
    public function run(GetPayrollReportQuery $query): array
    {
        $employees = $this->employeeRepository->findEmployeesBy(
            $query->getFilters(),
            $query->getOrderBy(),
        );

        $payrollRows = [];

        foreach ($employees as $employee) {
            $payrollRows[] = $this->payrollRowMapper->map($employee);
        }

        return $payrollRows;
    }
}
