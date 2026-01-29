<?php declare(strict_types=1);

namespace App\Tests\Unit\Payroll\Application\PayrollReport;

use App\Payroll\Application\PayrollReport\GetPayrollReportQuery;
use App\Payroll\Application\PayrollReport\GetPayrollReportQueryHandler;
use App\Payroll\Application\PayrollReport\PayrollRowDto;
use App\Payroll\Application\PayrollReport\PayrollRowMapper;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Employee\EmployeeRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class GetPayrollReportQueryHandlerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRunReturnsMappedDtos(): void
    {
        // Given
        $employeeRepository = $this->createMock(EmployeeRepository::class);
        $mapper = $this->createMock(PayrollRowMapper::class);

        $query = new GetPayrollReportQuery(
            filters: ['name' => 'John', 'department' => 'IT'],
            orderBy: 'surname',
        );

        $employee1 = $this->createMock(Employee::class);
        $employee2 = $this->createMock(Employee::class);

        $employeeRepository
            ->method('findEmployeesBy')
            ->with($query->getFilters(), $query->getOrderBy())
            ->willReturn([$employee1, $employee2]);

        $dto1 = $this->createMock(PayrollRowDto::class);
        $dto2 = $this->createMock(PayrollRowDto::class);

        $mapper
            ->method('map')
            ->willReturnMap([
                [$employee1, $dto1],
                [$employee2, $dto2],
            ]);

        $handler = new GetPayrollReportQueryHandler($employeeRepository, $mapper);

        // When
        $result = $handler->run($query);

        // Then
        $this->assertSame([$dto1, $dto2], $result);
    }
}
