<?php declare(strict_types=1);

namespace App\Payroll\Application\AddDepartment;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\DepartmentRepository;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Domain\Department\Value\BonusType;

class AddDepartmentCommandHandler
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
    ) {
    }

    /**
     * @throws DepartmentException
     */
    public function run(AddDepartmentCommand $command): void
    {
        $department = new Department(
            $command->getName(),
            BonusType::create($command->getBonusType()),
            $command->getBonusRate(),
            $command->getBonusYearsLimit() ?: null,
        );

        $this->departmentRepository->save($department);
        $this->departmentRepository->flush();
    }
}
