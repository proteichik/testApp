<?php

namespace CsvBundle\Traits;

trait CsvHeadersTrait
{
    protected $headers = array(
        'Product Code' => 'strProductCode',
        'Product Name' => 'strProductName',
        'Product Description' => 'strProductDesc',
        'Stock' => 'stock',
        'Cost in GBP' => 'cost',
        'Discontinued' => 'discontinued'
    );
    
    public function setData(array $data = array())
    {
        foreach ($data as $key => $value) {

            $key = $this->keyTransform($key);

            if (property_exists($this, $key)) {

                $this->$key = $value;
            }
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function keyTransform($key)
    {
         return (array_key_exists($key, $this->headers)) ? $this->headers[$key] : $key;
    }
}