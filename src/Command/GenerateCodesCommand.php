<?php

namespace App\Command;

use App\Services\UserCodeGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-codes',
    description: 'Add a short description for your command',
)]
class GenerateCodesCommand extends Command
{
    public function __construct(private readonly UserCodeGenerator $generator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('first', InputArgument::OPTIONAL, 'First ticket ID in BO', 0);
        $this->addArgument('last', InputArgument::OPTIONAL, 'Last ticket ID in BO', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $first = $input->getArgument('first');
        $last = $input->getArgument('last');

        $this->generator->generate($first, $last);
        $io->success(sprintf('%s codes have been generated', ($last - $first)));

        return Command::SUCCESS;
    }
}
