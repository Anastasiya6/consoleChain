<?php

namespace App\ChainCommandBundle\FooBundle\Command;

use App\ChainCommandBundle\FooBundle\Services\CommandChainService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FooHelloCommand extends Command
{
    protected static $defaultName = 'foo:hello';
    private $commandChainService;
    private $logger;


    public function __construct(CommandChainService $commandChainService,LoggerInterface $logger)
    {
        parent::__construct();
        $this->commandChainService = $commandChainService;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * @throws \Symfony\Component\Console\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandChainService->initChain();

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }
        $this->logger->info('Executing foo:hello command itself first:');

        $io->success('Hello from Foo!');

        $this->commandChainService->executeChain($this->getApplication(),$output);

        $this->logger->info('Execution of foo:hello chain completed.');
        return Command::SUCCESS;
    }
}