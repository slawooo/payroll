<?php declare(strict_types=1);

namespace App\Tests\Integration\Payroll\Application\AddDepartment;

use App\Payroll\Application\AddDepartment\AddDepartmentCommand;
use App\Payroll\Application\AddDepartment\AddDepartmentCommandHandler;
use App\Payroll\Domain\Department\DepartmentRepository;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Tests\Integration\Integration;

final class AddDepartmentCommandHandlerTest extends Integration
{
    private AddDepartmentCommandHandler $handler;

    private DepartmentRepository $departmentRepository;

    protected function setUp(): void
    {
        $this->handler = self::getContainer()->get(AddDepartmentCommandHandler::class);
        $this->departmentRepository = self::getContainer()->get(DepartmentRepository::class);
    }

    public function testAddsDepartmentToDatabase(): void
    {
        // Given
        $command = new AddDepartmentCommand(
            name: 'IT',
            bonusType: BonusType::FIXED,
            bonusRate: 100.0,
            bonusYearsLimit: 5,
        );

        $this->assertNull($this->departmentRepository->findOneBy(['name' => 'IT']));

        // When
        $this->handler->run($command);

        // Then
        $department = $this->departmentRepository->findOneBy(['name' => 'IT']);

        $this->assertSame('IT', $department->getName());
        $this->assertTrue($department->getBonusType()->isFixed());
    }

    public function testThrowsExceptionForInvalidBonusType(): void
    {
        // Given
        $command = new AddDepartmentCommand(
            name: 'IT',
            bonusType: 'unknown',
            bonusRate: 100.0,
            bonusYearsLimit: null,
        );

        // Then
        $this->expectException(DepartmentException::class);

        // When
        $this->handler->run($command);
    }
}
