<?php declare(strict_types=1);

namespace App\Payroll\Ui\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'example',
)]
class ExampleCliCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Hello world!');

        return Command::SUCCESS;
    }
}
