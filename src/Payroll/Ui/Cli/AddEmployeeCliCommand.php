<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli;

use App\Payroll\Application\AddEmployee\AddEmployeeCommand;
use App\Payroll\Application\AddEmployee\AddEmployeeCommandHandler;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Ui\Cli\Validation\DateArgumentValidator;
use App\Payroll\Ui\Cli\Validation\NumericArgumentValidator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'payroll:add-employee',
)]
class AddEmployeeCliCommand extends Command
{
    public function __construct(
        private readonly AddEmployeeCommandHandler $addEmployeeCommandHandler,
        private readonly NumericArgumentValidator $numericArgumentCliValidator,
        private readonly DateArgumentValidator $dateArgumentValidator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('department', InputArgument::REQUIRED, 'Department name');
        $this->addArgument('name', InputArgument::REQUIRED, 'First name');
        $this->addArgument('surname', InputArgument::REQUIRED, 'Last name');
        $this->addArgument('hireDate', InputArgument::REQUIRED, 'Hire date in YYYY-MM-DD format');
        $this->addArgument('baseSalary', InputArgument::REQUIRED, 'Base salary in USD');
    }

    /**
     * @throws DepartmentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hireDate = $input->getArgument('hireDate');
        $this->dateArgumentValidator->validate($hireDate, 'hireDate');

        $baseSalary = $input->getArgument('baseSalary');
        $this->numericArgumentCliValidator->validate($baseSalary, 'baseSalary');

        $this->addEmployeeCommandHandler->run(new AddEmployeeCommand(
            $input->getArgument('department'),
            $input->getArgument('name'),
            $input->getArgument('surname'),
            $hireDate,
            (float) $baseSalary,
        ));

        $output->writeln('Employee added successfully!');

        return Command::SUCCESS;
    }
}
