<?php declare(strict_types=1);

namespace App\Payroll\Application\AddEmployee;

use App\Payroll\Domain\Department\DepartmentRepository;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Employee\EmployeeRepository;
use App\Payroll\Domain\Shared\Value\Money;

class AddEmployeeCommandHandler
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly DepartmentRepository $departmentRepository,
    ) {
    }

    /**
     * @throws DepartmentException
     */
    public function run(AddEmployeeCommand $command): void
    {
        $departmentName = $command->getDepartment();
        $department = $this->departmentRepository->findOneBy(['name' => $departmentName]);

        if (!$department) {
            throw new DepartmentException("Department $departmentName not found");
        }

        $employee = new Employee(
            $command->getName(),
            $command->getSurname(),
            $department,
            $command->getYearsOfWork(),
            Money::create($command->getBaseSalary()),
        );

        $this->employeeRepository->save($employee);
        $this->employeeRepository->flush();
    }
}
