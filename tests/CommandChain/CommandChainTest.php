<?php

namespace App\Tests\CommandChain;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Psr\Log\LoggerInterface;

class CommandChainTest extends WebTestCase
{
    public function testExecuteChain()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $logger = $this->createMock(LoggerInterface::class);

        $output = $this->executeChain($application);

        $this->assertStringContainsString('Hi from Bar!', $output);
    }

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

    private function registerCommands(): array
    {
        return [
            'bar:hi'
        ];
    }
}