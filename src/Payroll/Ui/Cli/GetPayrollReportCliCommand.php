<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli;

use App\Payroll\Application\PayrollReport\GetPayrollReportQuery;
use App\Payroll\Application\PayrollReport\GetPayrollReportQueryHandler;
use App\Payroll\Application\PayrollReport\PayrollRowDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'payroll:get-payroll-report',
)]
class GetPayrollReportCliCommand extends Command
{
    public function __construct(
        private readonly GetPayrollReportQueryHandler $getPayrollReportQueryHandler,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Filter by employee name');
        $this->addOption('surname', null, InputOption::VALUE_OPTIONAL, 'Filter by employee surname');
        $this->addOption('department', null, InputOption::VALUE_OPTIONAL, 'Filter by department name');
        $this->addOption('order-by', null, InputOption::VALUE_OPTIONAL, 'Sort field');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filters = [
            'name' => $input->getOption('name'),
            'surname' => $input->getOption('surname'),
            'department' => $input->getOption('department'),
        ];

        $query = new GetPayrollReportQuery(
            $filters,
            $input->getOption('order-by') ?: null,
        );

        $payrollRows = $this->getPayrollReportQueryHandler->run($query);

        $table = new Table($output);
        $table->setHeaders($this->getTableHeader());

        foreach ($payrollRows as $payrollRow) {
            $table->addRow($this->getTableRow($payrollRow));
        }

        $table->render();

        return Command::SUCCESS;
    }

    private function getTableHeader(): array
    {
        return [
            'Name',
            'Surname',
            'Department',
            'Base',
            'Addition',
            'Bonus type',
            'Total',
        ];
    }

    private function getTableRow(PayrollRowDto $payrollRowDto): array
    {
        return [
            $payrollRowDto->getName(),
            $payrollRowDto->getSurname(),
            $payrollRowDto->getDepartment(),
            $payrollRowDto->getBaseSalary(),
            $payrollRowDto->getBonus(),
            $payrollRowDto->getBonusType(),
            $payrollRowDto->getTotalSalary(),
        ];
    }
}
