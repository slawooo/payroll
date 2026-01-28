<?php declare(strict_types=1);

namespace App\Tests\Functional\Payroll\Ui\Cli;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Employee\EmployeeRepository;
use App\Tests\Functional\Functional;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class AddEmployeeCliCommandTest extends Functional
{
    private Application $application;

    private EmployeeRepository $employeeRepository;

    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        $this->application = new Application(self::$kernel);
        $this->application->setAutoExit(false);
        $this->employeeRepository = self::getContainer()->get(EmployeeRepository::class);
    }

    public function testExecute(): void
    {
        // Given
        $this->addDepartment();

        $command = $this->application->find('payroll:add-employee');
        $tester = new CommandTester($command);

        $this->assertEmpty($this->employeeRepository->findEmployeesBy());

        // When
        $exitCode = $tester->execute([
            'department' => 'IT',
            'name' => 'John',
            'surname' => 'Doe',
            'hireDate' => (new DateTimeImmutable())->modify('-3 years')->format('Y-m-d'),
            'baseSalary' => '10000.00',
        ]);

        // Then
        $this->assertSame(Command::SUCCESS, $exitCode);
        $output = $tester->getDisplay();
        $this->assertStringContainsString('Employee added successfully!', $output);

        /** @var Employee|null $employee */
        $employees = $this->employeeRepository->findEmployeesBy(['name' => 'John', 'surname' => 'Doe']);
        $this->assertCount(1, $employees);

        $employee = $employees[0];
        $this->assertSame('John', $employee->getName());
        $this->assertSame('Doe', $employee->getSurname());
        $this->assertSame('IT', $employee->getDepartment()->getName());
        $this->assertSame(1000000, $employee->getBaseSalary()->getAmountInCents());
        $this->assertSame(30000, $employee->getBonus()->getAmountInCents());
        $this->assertSame(1030000, $employee->getTotalSalary()->getAmountInCents());
    }

    private function addDepartment(): void
    {
        $department = new Department(
            name: 'IT',
            bonusType: BonusType::create(BonusType::FIXED),
            bonusRate: 100.0,
            bonusYearsLimit: 5,
        );

        $em = self::getContainer()->get(EntityManagerInterface::class);
        $em->persist($department);
        $em->flush();
    }

    public function testExecuteWithNonNumericBaseSalary(): void
    {
        // Given
        $command = $this->application->find('payroll:add-employee');
        $tester = new CommandTester($command);

        // Then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('baseSalary must be numeric');

        // When
        $tester->execute([
            'department' => 'IT',
            'name' => 'John',
            'surname' => 'Doe',
            'hireDate' => '2020-01-26',
            'baseSalary' => 'not-a-number', // invalid value
        ]);
    }

    public function testExecuteWithInvalidHireDate(): void
    {
        // Given
        $command = $this->application->find('payroll:add-employee');
        $tester = new CommandTester($command);

        // Then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('hireDate must be a valid date in format YYYY-MM-DD');

        // When
        $tester->execute([
            'department' => 'IT',
            'name' => 'John',
            'surname' => 'Doe',
            'hireDate' => '2020-02-30', // invalid value
            'baseSalary' => '10000.00',
        ]);
    }
}
