<?php

namespace Tests\CsvBundle\Command;

use CsvBundle\Command\ImportCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ImportCommandTest
 * @package Tests\CsvBundle\Command
 */
class ImportCommandTest extends KernelTestCase
{
    private $commandTester;

    public function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();


        $app = new Application($kernel);
        $app->add(new ImportCommand());
        $command = $app->find('import:command');

        $this->commandTester = new CommandTester($command);
    }

    /**
     * Testing how command execute with invalid format
     */
    public function testExecuteWithBadFormat()
    {

        $this->commandTester->execute(
            array(
                'format'    => 'cv',
                'file' => __DIR__ . '/../Fixtures/stock_valid.csv',
                '--test'  => true,
            )
        );

        $this->assertEquals('Reader for type cv not found' . PHP_EOL, $this->commandTester->getDisplay());

    }

    /**
     * Testing how command execute with invalid file
     */
    public function testExecuteWithBadFile()
    {

        $this->commandTester->execute(
            array(
                'format'    => 'csv',
                'file' => __DIR__. '/../Fixtures/stock.csv',
                '--test'  => true,
            )
        );

        $this->assertEquals('File not found' . PHP_EOL, $this->commandTester->getDisplay());

    }

    /**
     * Testing how command execute with valid format and file
     */
    public function testExecute()
    {
        $this->commandTester->execute(
            array(
                'format'    => 'csv',
                'file' => __DIR__. '/../Fixtures/stock_valid.csv',
                '--test'  => true,
            )
        );

        $this->assertEquals('Total: 27 objects. Imported: 23, not imported: 4' . PHP_EOL, $this->commandTester->getDisplay());
    }
}