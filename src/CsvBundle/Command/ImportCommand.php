<?php

namespace CsvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use CsvBundle\Factory\ImportFactory;
use CsvBundle\Exception\FormatNotFoundException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class ImportCommand
 * @package CsvBundle\Command
 * Command for import product
 */
class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:command')
            ->setDescription('Importing product to database. Choise format (csv) and file.')
            ->addArgument(
                'format',
                InputArgument::REQUIRED,
                'Type a format: '
            )
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Type a path to file: '
            )
            ->addOption(
                'test',
                null,
                InputOption::VALUE_NONE,
                'If you want run a test mode'
            )
            ->addOption(
              'clear',
                null,
                InputOption::VALUE_NONE,
                'If you want to clear your table'
            );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $format = $input->getArgument('format'); //format import
        $file = $input->getArgument('file'); //path to file
        
        try
        {
            $reader = ImportFactory::getReader($format, $file); //get the reader
        } catch (FormatNotFoundException $ex)
        {
            // if format is invalid
            $output->writeln('<error>Reader for type '. $format. ' not found</error>');
            return;
        } catch (FileNotFoundException $ex) {
            //if file not found
            $output->writeln('<error>File not found</error>');
            return;
        }

        $importService = $this->getContainer()->get('csv.service.import_service'); //import service

        //Clearing table (if need)
        if ($input->getOption('clear'))
        {
            $importService->clearProductTable();
        }

        $result = $importService->importObjects($reader, $input->getOption('test')); //main function for importing objects

        $message = sprintf('Total: %s objects. Imported: %s, not imported: %s', $reader->count(), $result['success'], $result['errors']);

        $output->writeln($message);
    }
}