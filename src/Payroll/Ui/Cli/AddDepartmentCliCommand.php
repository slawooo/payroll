<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli;

use App\Payroll\Application\AddDepartment\AddDepartmentCommand;
use App\Payroll\Application\AddDepartment\AddDepartmentCommandHandler;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'payroll:add-department',
)]
class AddDepartmentCliCommand extends Command
{
    public function __construct(
        private readonly AddDepartmentCommandHandler $addDepartmentCommandHandler,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addArgument('bonusType', InputArgument::REQUIRED);
        $this->addArgument('bonusRate', InputArgument::REQUIRED);
        $this->addArgument('bonusYearsLimit', InputArgument::OPTIONAL);
    }

    /**
     * @throws DepartmentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bonusYearsLimit = $input->getArgument('bonusYearsLimit');

        if ($bonusYearsLimit !== null) {
            $bonusYearsLimit = (int) $bonusYearsLimit;
        }

        $this->addDepartmentCommandHandler->run(new AddDepartmentCommand(
            $input->getArgument('name'),
            $input->getArgument('bonusType'),
            (float) $input->getArgument('bonusRate'),
            $bonusYearsLimit,
        ));

        $output->writeln('Department added successfully!');

        return Command::SUCCESS;
    }
}
