<?php declare(strict_types=1);

namespace App\Tests\Functional\Payroll\Ui\Cli;

use App\Payroll\Domain\Department\DepartmentRepository;
use App\Tests\Functional\Functional;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class AddDepartmentCliCommandTest extends Functional
{
    private Application $application;

    private DepartmentRepository $departmentRepository;

    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        $this->application = new Application(self::$kernel);
        $this->application->setAutoExit(false);
        $this->departmentRepository = self::getContainer()->get(DepartmentRepository::class);
    }

    public function testExecute(): void
    {
        // Given
        $command = $this->application->find('payroll:add-department');
        $tester = new CommandTester($command);

        $this->assertNull($this->departmentRepository->findOneBy(['name' => 'IT']));

        // When
        $exitCode = $tester->execute([
            'name' => 'IT',
            'bonusType' => 'fixed',
            'bonusRate' => '100.0',
            'bonusYearsLimit' => '5',
        ]);

        // Then
        $this->assertSame(Command::SUCCESS, $exitCode);
        $output = $tester->getDisplay();
        $this->assertStringContainsString('Department added successfully!', $output);

        $department = $this->departmentRepository->findOneBy(['name' => 'IT']);
        $this->assertSame('IT', $department->getName());
    }
}
