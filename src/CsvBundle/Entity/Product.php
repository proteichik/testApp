<?php

/**
 * Product entity
 */

namespace CsvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use CsvBundle\Traits\CsvHeadersTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @package CsvBundle\Entity
 * @ORM\Entity(repositoryClass="CsvBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="tblProductData")
 */
class Product
{
    use CsvHeadersTrait;
    
    /**
     * @var int
     * @ORM\Column(name="intProductDataId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $intProductDataId;

    /**
     * @var string
     * @ORM\Column(name="strProductName", type="string", length=50)
     * @Assert\NotBlank();
     */
    protected $strProductName;

    /**
     * @var string
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     * @Assert\NotBlank();
     */
    protected $strProductDesc;

    /**
     * @var string
     * @ORM\Column(name="strProductCode", type="string", length=10)
     * @Assert\NotBlank()
     */
    protected $strProductCode;

    /**
     * @var DateTime
     * @ORM\Column(name="dtmAdded", type="datetime")
     */
    protected $dtmAdded;

    /**
     * @var DateTime
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    protected $dtmDiscontinued;

    /**
     * @var int
     * @Assert\Type(type="numeric")
     * @Assert\NotBlank()
     */
    protected $stock;

    /**
     * @var float
     * @Assert\Type(type="numeric")
     * @Assert\NotBlank()
     */
    protected $cost;

    /**
     * @var string|null
     */
    protected $discontinued;


    /**
     * @param $stock
     * @return $this
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param $discontinued
     * @return $this
     */
    public function setDiscontinued($discontinued)
    {
        $this->discontinued = $discontinued;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * Get intProductDataId
     *
     * @return integer
     */
    public function getIntProductDataId()
    {
        return $this->intProductDataId;
    }

    /**
     * Set strProductName
     *
     * @param string $strProductName
     *
     * @return Product
     */
    public function setStrProductName($strProductName)
    {
        $this->strProductName = $strProductName;

        return $this;
    }

    /**
     * Get strProductName
     *
     * @return string
     */
    public function getStrProductName()
    {
        return $this->strProductName;
    }

    /**
     * Set strProductDesc
     *
     * @param string $strProductDesc
     *
     * @return Product
     */
    public function setStrProductDesc($strProductDesc)
    {
        $this->strProductDesc = $strProductDesc;

        return $this;
    }

    /**
     * Get strProductDesc
     *
     * @return string
     */
    public function getStrProductDesc()
    {
        return $this->strProductDesc;
    }

    /**
     * Set strProductCode
     *
     * @param string $strProductCode
     *
     * @return Product
     */
    public function setStrProductCode($strProductCode)
    {
        $this->strProductCode = $strProductCode;

        return $this;
    }

    /**
     * Get strProductCode
     *
     * @return string
     */
    public function getStrProductCode()
    {
        return $this->strProductCode;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDtmAdded()
    {
        $this->dtmAdded = new \DateTime();

    }

    /**
     * Get dtmAdded
     *
     * @return \DateTime
     */
    public function getDtmAdded()
    {
        return $this->dtmAdded;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDtmDiscontinued()
    {

        if ($this->discontinued === 'yes') {
            $this->dtmDiscontinued = new \DateTime();
        }

    }

    /**
     * Get dtmDiscontinued
     *
     * @return \DateTime
     */
    public function getDtmDiscontinued()
    {
        return $this->dtmDiscontinued;
    }

       

    /**
     * @Assert\IsFalse()
     * @return bool
     */
    public function isProductLessCostAndStock()
    {
        return ($this->cost < 5 && $this->stock < 10);
    }

    /**
     * @Assert\IsFalse()
     * @return bool
     */
    public function isProductOverCost()
    {
        return ($this->cost > 1000);
    }



}
