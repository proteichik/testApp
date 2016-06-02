<?php

namespace CsvBundle\Event;


class ProductFailEvent extends ProductEvent
{
    const FAIL_IMPORT = 'csv.import.fail';
    
    protected $errors; //validations error

    public function setErrors(\IteratorAggregate $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}