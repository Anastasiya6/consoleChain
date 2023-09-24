<?php

namespace App\Services;

use App\Services\Application;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class CommandChainService
{

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Initializes the command chain.
     *
     * This method logs information about the master command (foo:hello) and its registered member commands
     * (commands that are part of the foo:hello command chain).
     */
    public function initChain(): void
    {
        $this->logger->info('foo:hello is a master command of a command chain that has registered member commands');

        $commands = $this->registerCommands();
        foreach($commands as $command) {
            $this->logger->info($command.' registered as a member of foo:hello command chain');
        }
    }
    /**
     * Executes a chain of commands.
     *
     * This method runs a set of commands in a loop and logs information about their execution.
     *
     * @param Application $application The Symfony Console application object.
     * @param OutputInterface $output The output interface for displaying execution results.
     * @throws \Exception If an error occurs during command execution.
     */
    public function executeChain($application,OutputInterface $output): void
    {
        $commands = $this->registerCommands();

        foreach($commands as $command) {

            $command = $application->find($command);

            $arguments = [
                'command' => $command,
            ];
            try {
                $command->run(new ArrayInput($arguments), $output);
                $this->logger->info('Hi from Bar!');
            } catch (\Exception $e) {

                echo 'An error occurred: ' . $e->getMessage();
            }
        }
    }
    /**
     * Array of commands that are used in the command chain.
     * @return array
     */
    public function getRegisterCommands(): array
    {
        return $this->registerCommands();
    }
    /**
     * Array of commands that are used in the command chain.
     * @return array
     */
    private function registerCommands(): array
    {
        return [
            'bar:hi'
        ];
    }

}