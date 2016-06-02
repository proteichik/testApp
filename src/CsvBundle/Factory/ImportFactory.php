<?php 
namespace CsvBundle\Factory;

use CsvBundle\Exception\FormatNotFoundException;
use Ddeboer\DataImport\Reader\CsvReader;

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
        
        switch ($format)
        {
            case 'csv': 
                $instance = new CsvReader(new \SplFileObject($target));
                $instance->setHeaderRowNumber(0);
                break;
            default:
                throw new FormatNotFoundException('Format not found');
        }

        return $instance;
    }
}