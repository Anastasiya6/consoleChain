<?php

namespace App\ChainCommandBundle\FooBundle\Command;

use App\Services\CommandChainService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class FooHelloCommand
 *
 * Represents the foo:hello console command.
 *
 * @package App\ChainCommandBundle\FooBundle\Command
 */
class FooHelloCommand extends Command
{
    /**
     * @var CommandChainService
     */
    private $commandChainService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * FooHelloCommand constructor.
     *
     * @param CommandChainService $commandChainService The command chain service.
     * @param LoggerInterface $logger The logger instance.
     */
    public function __construct(CommandChainService $commandChainService, LoggerInterface $logger)
    {
        parent::__construct();
        $this->commandChainService = $commandChainService;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('foo:hello');
    }

    /**
     * Executes the foo:hello command.
     *
     * @param InputInterface $input The input interface.
     * @param OutputInterface $output The output interface.
     *
     * @return int The command result code.
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandChainService->initChain();

        $io = new SymfonyStyle($input, $output);

        $this->logger->info('Executing foo:hello command itself first:');

        $io->success('Hello from Foo!');

        $this->logger->info('Hello from Foo!');

        $this->commandChainService->executeChain($this->getApplication(), $output);

        $this->logger->info('Execution of foo:hello chain completed.');

        return Command::SUCCESS;
    }
}