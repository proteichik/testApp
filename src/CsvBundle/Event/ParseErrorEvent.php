<?php

namespace CsvBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ParseErrorEvent
 * @package CsvBundle\Event
 * Event object for parsing errors
 */
class ParseErrorEvent extends Event
{

    const PARSE_ERROR = 'csv.parse.error';
    
    protected $lines = array(); //lines num. of bad object

    /**
     * Get array of lines
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Set num. lines
     * @param array $lines
     */
    public function setLines(array $lines)
    {
        $this->lines = $lines;
    }
}