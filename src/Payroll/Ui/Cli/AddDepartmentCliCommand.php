<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli;

use App\Payroll\Application\AddDepartment\AddDepartmentCommand;
use App\Payroll\Application\AddDepartment\AddDepartmentCommandHandler;
use App\Payroll\Domain\Department\Exception\DepartmentException;
use App\Payroll\Ui\Cli\Validation\NumericArgumentValidator;
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
        private readonly NumericArgumentValidator $numericArgumentCliValidator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Department name');
        $this->addArgument('bonusType', InputArgument::REQUIRED, 'Valid values: [fixed,percentage]');
        $this->addArgument('bonusRate', InputArgument::REQUIRED, 'Bonus increment or percent');
        $this->addArgument('bonusYearsLimit', InputArgument::OPTIONAL, 'Limit in years for which bonus applies');
    }

    /**
     * @throws DepartmentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bonusYearsLimit = $input->getArgument('bonusYearsLimit');

        if ($bonusYearsLimit !== null) {
            $this->numericArgumentCliValidator->validate($bonusYearsLimit, 'bonusYearsLimit');
            $bonusYearsLimit = (int) $bonusYearsLimit;
        }

        $bonusRate = $input->getArgument('bonusRate');
        $this->numericArgumentCliValidator->validate($bonusRate, 'bonusRate');

        $this->addDepartmentCommandHandler->run(new AddDepartmentCommand(
            $input->getArgument('name'),
            $input->getArgument('bonusType'),
            (float) $bonusRate,
            $bonusYearsLimit,
        ));

        $output->writeln('Department added successfully!');

        return Command::SUCCESS;
    }
}
