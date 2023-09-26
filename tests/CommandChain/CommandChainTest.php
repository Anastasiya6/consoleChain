<?php

namespace App\Tests\CommandChain;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Psr\Log\LoggerInterface;

/**
 * Class CommandChainTest
 *
 * @package App\Tests\CommandChain
 */
class CommandChainTest extends WebTestCase
{
    /**
     * Test the execution of a command chain.
     */
    public function testExecuteChain()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $logger = $this->createMock(LoggerInterface::class);

        $output = $this->executeChain($application);

        $this->assertStringContainsString('Hi from Bar!', $output);
    }

    /**
     * Execute a chain of commands and return the combined output.
     *
     * @param Application $application The Symfony Console application object.
     *
     * @return string The combined output of the executed commands.
     */
    private function executeChain(Application $application): string
    {
        $commands = $this->registerCommands();
        $output = '';

        foreach ($commands as $commandName) {
            $command = $application->find($commandName);
            $commandTester = new CommandTester($command);

            try {
                $commandTester->execute(['command' => $commandName]);
                $output .= $commandTester->getDisplay();
            } catch (\Exception $e) {
                $output .= 'An error occurred: ' . $e->getMessage();
            }
            $this->assertEquals(0, $commandTester->getStatusCode());
        }

        return $output;
    }

    /**
     * Register the commands for the command chain.
     *
     * @return array An array of command names to be executed.
     */
    private function registerCommands(): array
    {
        return [
            'bar:hi',
        ];
    }
}