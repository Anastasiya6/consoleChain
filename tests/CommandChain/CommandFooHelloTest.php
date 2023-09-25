<?php

namespace App\Tests\CommandChain;

use App\Services\CommandChainService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Psr\Log\LoggerInterface;
use App\ChainCommandBundle\FooBundle\Command\FooHelloCommand;

class CommandFooHelloTest extends WebTestCase
{
    public function testCommandChain()
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

        }
}