<?php


namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AdvancedCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('command:advance')
            ->setDescription('This command will ask you for name and surname and print them back.')
            ->addArgument('surname', InputArgument::REQUIRED)
            ->addArgument('name', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        $surname = $input->getArgument('surname');

        $io->success(\sprintf('Ваше имя: %s %s', $surname, $name));

        return 1;
    }
}