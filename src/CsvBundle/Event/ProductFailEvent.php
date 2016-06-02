<?php

namespace CsvBundle\Event;


use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductFailEvent extends ProductEvent
{
    const FAIL_IMPORT = 'csv.import.fail';
    
    protected $errors; //validations error

    public function setErrors(ConstraintViolationListInterface $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}