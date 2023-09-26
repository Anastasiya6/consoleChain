<?php

namespace App\ChainCommandBundle\BarBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BarHiCommand
 *
 * Represents the bar:hi console command.
 *
 * @package App\ChainCommandBundle\BarBundle\Command
 */
class BarHiCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('bar:hi');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success('Hi from Bar!');

        return Command::SUCCESS;
    }
}