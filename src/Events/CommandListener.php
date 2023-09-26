<?php

namespace App\Events;

use App\Services\CommandChainService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CommandListener
 *
 * Listens for console command events and handles command chain membership.
 *
 * @package App\Events
 */
class CommandListener implements EventSubscriberInterface
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
     * CommandListener constructor.
     *
     * @param CommandChainService $commandChainService The command chain service.
     * @param LoggerInterface $logger The logger instance.
     */
    public function __construct(CommandChainService $commandChainService, LoggerInterface $logger)
    {
        $this->commandChainService = $commandChainService;
        $this->logger = $logger;
    }

    /**
     * Handles the console command event.
     *
     * Checks if a command is a member of a command chain and prevents standalone execution.
     *
     * @param ConsoleCommandEvent $event The console command event.
     */
    public function onCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        $output = $event->getOutput();

        if (in_array($command->getName(), $this->commandChainService->getRegisterCommands())) {
            $output->writeln('Error: ' . $command->getName() . ' command is a member of foo:hello command chain and cannot be executed on its own.');
            $event->disableCommand();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'onCommand',
        ];
    }
}