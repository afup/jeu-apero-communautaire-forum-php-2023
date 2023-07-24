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
        $this->addArgument('number', InputArgument::OPTIONAL, 'How many codes you want to generate', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $number = $input->getArgument('number');

        $this->generator->generate($number);
        $io->success(sprintf('%s codes have been generated', $number));

        return Command::SUCCESS;
    }
}
