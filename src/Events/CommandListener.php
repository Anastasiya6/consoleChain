<?php

namespace App\Events;

use App\ChainCommandBundle\FooBundle\Services\CommandChainService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommandListener implements EventSubscriberInterface
{
    private $commandChainService;
    private $logger;
    private $application;

    public function __construct(CommandChainService $commandChainService,LoggerInterface $logger)
    {
        $this->commandChainService = $commandChainService;
        $this->logger = $logger;
    }
    public function onCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        $output = $event->getOutput();

        if(in_array($command->getName(),$this->commandChainService->getRegisterCommands()))
        {
            $output->writeln('Error: '.$command->getName().' command is a member of foo:hello command chain and cannot be executed on its own.');
            $event->disableCommand();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => 'onCommand',
        ];
    }
}