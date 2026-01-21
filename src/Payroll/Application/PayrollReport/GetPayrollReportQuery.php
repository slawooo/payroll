<?php declare(strict_types=1);

namespace App\Payroll\Application\PayrollReport;

class GetPayrollReportQuery
{
    public function __construct(
        private readonly array $filters,
        private readonly ?string $orderBy = null,
    ){
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }
}
