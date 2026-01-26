<?php declare(strict_types=1);

namespace App\Tests\Functional\Payroll\Ui\Cli;

use App\Payroll\Domain\Department\Department;
use App\Payroll\Domain\Department\Value\BonusType;
use App\Payroll\Domain\Employee\Employee;
use App\Payroll\Domain\Shared\Value\Money;
use App\Tests\Functional\Functional;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Tester\CommandTester;

final class GetPayrollReportCliCommandTest extends Functional
{
    private Application $application;

    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();
        $this->application = new Application(self::$kernel);
        $this->application->setAutoExit(false);
        $this->em = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testGetPayrollReportPrintsExpectedTableUsingHelper(): void
    {
        // Given
        $this->seedData();
        $command = $this->application->find('payroll:get-payroll-report');
        $tester = new CommandTester($command);

        // When
        $exitCode = $tester->execute(['--name' => 'Anna', '--order-by' => 'surname']);

        // Then
        $this->assertSame(Command::SUCCESS, $exitCode);
        $cliOutput = $tester->getDisplay();
        $expectedOutput = $this->renderExpectedTable();
        $this->assertSame($expectedOutput, $cliOutput);
    }

    private function renderExpectedTable(): string
    {
        $buffer = new BufferedOutput();
        $table = new Table($buffer);
        $table->setHeaders(['Name', 'Surname', 'Department', 'Base', 'Addition', 'Bonus type', 'Total']);
        $table->addRow(['Anna', 'Brown', 'HR', '$7,000.00', '$1,200.00', 'fixed', '$8,200.00']);
        $table->addRow(['Anna', 'Smith', 'IT', '$8,000.00', '$1,500.00', 'fixed', '$9,500.00']);
        $table->render();

        return $buffer->fetch();
    }

    private function seedData(): void
    {
        $it = $this->createDepartment('IT', 500);
        $hr = $this->createDepartment('HR', 400);

        $this->em->persist($it);
        $this->em->persist($hr);

        $johnIt = $this->createEmployee('John', 'Doe', $it, 9000);
        $annaIt = $this->createEmployee('Anna', 'Smith', $it, 8000);
        $annaHr = $this->createEmployee('Anna', 'Brown', $hr, 7000);

        $this->em->persist($johnIt);
        $this->em->persist($annaIt);
        $this->em->persist($annaHr);

        $this->em->flush();
    }

    private function createDepartment(string $name, float $bonusRate): Department
    {
        return new Department(
            name: $name,
            bonusType: BonusType::create(BonusType::FIXED),
            bonusRate: $bonusRate,
            bonusYearsLimit: 5,
        );
    }

    private function createEmployee(string $name, string $surname, Department $department, float $salary): Employee
    {
        return new Employee(
            name: $name,
            surname: $surname,
            department: $department,
            hireDate: (new DateTimeImmutable())->modify('-3 years'),
            baseSalary: Money::create($salary),
        );
    }
}
