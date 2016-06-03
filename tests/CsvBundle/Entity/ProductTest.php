<?php

namespace Tests\CsvBundle\Entity;

use CsvBundle\Entity\Product;

/**
 * Class CvsProductTest
 * @package Tests\CsvBundle\Entity
 */
class CvsProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing empty product
     */
    public function testGetEmptyProduct()
    {
        $product = new Product();

        $this->assertNull($product->getDiscontinued());
        $this->assertNull($product->getCost());
        $this->assertNull($product->getDtmAdded());
        $this->assertNull($product->getDtmDiscontinued());
        $this->assertNull($product->getStock());
        $this->assertNull($product->getIntProductDataId());
        $this->assertNull($product->getStrProductCode());
        $this->assertNull($product->getStrProductDesc());
        $this->assertNull($product->getStrProductName());


    }

    /**
     * Testing product with data
     */
    public function testGetProductWithData()
    {
        $product_data = array(
            'Product Code' => 'P0001',
            'Product Name' => 'TV',
            'Product Description' => '32” Tv',
            'Stock' => '10',
            'Cost in GBP' => '399.99'
        );

        $product = new Product();
        $product->setData($product_data);

        $this->assertEquals('P0001', $product->getStrProductCode());
        $this->assertEquals('TV', $product->getStrProductName());
        $this->assertEquals('32” Tv', $product->getStrProductDesc());
        $this->assertEquals(10, $product->getStock());
        $this->assertEquals(399.99, $product->getCost());
        $this->assertNull($product->getDiscontinued());
    }

    /**
     * Testing function isProductLessCostAndStock().
     */
    public function testLessData()
    {
        $product = new Product();
        $product->setStock(9);
        $product->setCost(10);

        $this->assertFalse($product->isProductLessCostAndStock());

        $product->setCost(3);

        $this->assertTrue($product->isProductLessCostAndStock());
    }

    /**
     * Testing function isProductOverCost().
     */
    public function testOverData()
    {
        $product = new Product();

        $product->setCost(999.9);
        $this->assertFalse($product->isProductOverCost());

        $product->setCost(1000.01);
        $this->assertTrue($product->isProductOverCost());
    }
}