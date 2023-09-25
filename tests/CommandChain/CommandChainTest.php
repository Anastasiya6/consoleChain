<?php

namespace App\Tests\CommandChain;

use App\Services\CommandChainService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use App\ChainCommandBundle\FooBundle\Command\FooHelloCommand;
use Psr\Log\LoggerInterface;

class CommandChainTest extends WebTestCase
{
    public function testExecuteChain()
    {
        // Создаем приложение Symfony
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $logger = $this->createMock(LoggerInterface::class);

        $output = $this->executeChain($application);
        echo $output;
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
        }

        return $output;
    }

    private function registerCommands(): array
    {
        return [
            'bar:hi'
        ];
    }

    /*public function testCommandChain()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $logger = $this->createMock(LoggerInterface::class);
        $commandChainService = $this->createMock(CommandChainService::class);
        $application->add(new FooHelloCommand($commandChainService,$logger));

        $command = $application->find('foo:hello');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertEquals(0, $commandTester->getStatusCode());

        $this->assertStringContainsString('Hello from Foo!', $commandTester->getDisplay());

        $barCommand = $application->find('bar:hi');
        $barCommandTester = new CommandTester($barCommand);
        $barCommandTester->execute([]);

        $this->assertEquals(0, $commandTester->getStatusCode());

        $this->assertStringContainsString('Hi from Bar!', $barCommandTester->getDisplay());

    }*/
}