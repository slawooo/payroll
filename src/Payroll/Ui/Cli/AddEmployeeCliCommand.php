<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli;

use App\Payroll\Application\AddEmployee\AddEmployeeCommand;
use App\Payroll\Application\AddEmployee\AddEmployeeCommandHandler;
use App\Payroll\Domain\Department\Exception\DepartmentException;
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
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('department', InputArgument::REQUIRED, 'Department name');
        $this->addArgument('name', InputArgument::REQUIRED, 'First name');
        $this->addArgument('surname', InputArgument::REQUIRED, 'Last name');
        $this->addArgument('yearsOfWork', InputArgument::REQUIRED, 'Number of years of work');
        $this->addArgument('baseSalary', InputArgument::REQUIRED, 'Base salary in USD');
    }

    /**
     * @throws DepartmentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->addEmployeeCommandHandler->run(new AddEmployeeCommand(
            $input->getArgument('department'),
            $input->getArgument('name'),
            $input->getArgument('surname'),
            (int) $input->getArgument('yearsOfWork'),
            (float) $input->getArgument('baseSalary'),
        ));

        $output->writeln('Employee added successfully!');

        return Command::SUCCESS;
    }
}
