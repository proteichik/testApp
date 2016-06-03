<?php

namespace Tests\CsvBundle\Factory;

use CsvBundle\Exception\FormatNotFoundException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\content\LargeFileContent;
use CsvBundle\Factory\ImportFactory;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Ddeboer\DataImport\Reader\CsvReader;

/**
 * Class ImportFactoryTest
 * @package Tests\CsvBundle\Factory
 */
class ImportFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing get reader of the bad format
     */
    public function testBadFormat()
    {
        $file = 'test.txt';
        $format = 'test';

        try{
            $reader = ImportFactory::getReader($format, $file);
            $this->fail('Must throw FormatNotFoundException');
        } catch(FormatNotFoundException $ex) {

        }
    }

    /**
     * Testing get reader of not exists file
     *
     * @throws FormatNotFoundException
     */
    public function testNotExistsFile()
    {
        $file = 'test.csv';
        $format = 'csv';

        try {
            $reader = ImportFactory::getReader($format, $file);
            $this->fail('Must throw FileNotFoundException');
        } catch (FileNotFoundException $ex) {

        }
    }

    /**
     * Testing get reader for valid data
     *
     * @throws FormatNotFoundException
     */
    public function testExistsFile()
    {
        $file = $this->getValidFile();
        $format = 'csv';

        $reader = ImportFactory::getReader($format, $file->url());

        $this->assertInstanceOf(CsvReader::class, $reader);
    }

    /**
     * Simulate the file
     *
     * @return $this
     */
    private function getValidFile()
    {
        $root = vfsStream::setup();
        return vfsStream::newFile('foo.csv', 0777)->withContent(LargeFileContent::withKilobytes(100))->at($root);
    }
}