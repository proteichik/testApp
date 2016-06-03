<?php

namespace Test\CsvBundle\Entity;

use CsvBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ProductValidationTest
 * @package Test\CsvBundle\Entity
 */
class ProductValidationTest extends KernelTestCase
{
    private $validator;
    private $product;

    public function setUp()
    {
        self::bootKernel();

        $this->validator = self::$kernel->getContainer()->get('validator');

        $this->product = new Product();
    }

    /**
     * Testing validation of empty product
     */
    public function testEmptyProduct()
    {

        $this->assertTrue(count($this->getErrors()) > 0);
    }

    /**
     * Testing validation of product with data
     */
    public function testProductWithData()
    {
        $product_data = array(
            'Product Code' => 'P0001',
            'Product Name' => 'TV',
            'Product Description' => '32” Tv',

        );

        $this->product->setData($product_data);
        $this->assertTrue(count($this->getErrors()) > 0); //Not valid(stock and cost is empty)

        $this->product->setStock('aaaa');
        $this->product->setCost('bbbb');

        $this->assertTrue(count($this->getErrors()) > 0); //Not valid (stock and cost not numeric)

        $this->product->setStock('111');
        $this->assertTrue(count($this->getErrors()) > 0); //Not valid (cost not numeric)

        $this->product->setCost('111');

        $this->assertFalse(count($this->getErrors()) > 0); //Valid!
    }

    /**
     * Get the validate errors
     *
     * @return Symfony\Component\Validator\ConstraintViolationListInterface
     */
    private function getErrors()
    {
        return $this->validator->validate($this->product);
    }

}