<?php

namespace CsvBundle\Event;


use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ProductFailEvent
 * @package CsvBundle\Event
 */
class ProductFailEvent extends ProductEvent
{
    const FAIL_IMPORT = 'csv.import.fail';
    
    protected $errors; //validations error

    /**
     * Set Errors
     * @param ConstraintViolationListInterface $errors
     */
    public function setErrors(ConstraintViolationListInterface $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Get errors
     * @return ConstraintViolationListInterface
     */
    public function getErrors()
    {
        return $this->errors;
    }

}