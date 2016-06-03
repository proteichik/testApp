<?php 
namespace CsvBundle\Factory;

use CsvBundle\Exception\FormatNotFoundException;
use Ddeboer\DataImport\Reader\CsvReader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class ImportFactory
 * @package CsvBundle\Factory
 * Класс-фабрика для импорта данных
 */
class ImportFactory
{
    /**
     * @param string $format
     * @param string $target
     * @return CsvReader|null
     * @throws FormatNotFoundException
     */
    public static function getReader($format, $target)
    {
        $instance = null;

        switch ($format) {
            case 'csv':
                $instance = self::getCsvReader($target);
                break;
            default:
                throw new FormatNotFoundException('Format not found');
        }

        return $instance;
    }

    /**
     * @param $file
     * @return CsvReader
     */
    protected static function getCsvReader($file)
    {
        try{
            $file = new \SplFileObject($file);
            $instance = new CsvReader($file);
            $instance->setHeaderRowNumber(0); //set headers
            return $instance;
        } catch (\Exception $ex)
        {
            throw new FileNotFoundException();
        }
    }
}