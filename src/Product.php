<?php

namespace Omnipay\Creditcall;

use Omnipay\Common\ParametersTrait;
class Product {
    use ParametersTrait;

    /**
     * Create a new Product object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * Get the product amount.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Sets the product amount.
     *
     * @param string $value
     * @return $this
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    /**
     * Get the product amount unit.
     *
     * @return string
     */
    public function getAmountUnit()
    {
        return $this->getParameter('amountUnit');
    }

    /**
     * Sets the product amount unit.
     *
     * @param string $value
     * @return $this
     */
    public function setAmountUnit($value)
    {
        return $this->setParameter('amountUnit', $value);
    }

    /**
     * Get the product category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->getParameter('category');
    }

    /**
     * Sets the product category.
     *
     * @param string $value
     * @return $this
     */
    public function setCategory($value)
    {
        return $this->setParameter('category', $value);
    }

    /**
     * Get the product code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->getParameter('code');
    }

    /**
     * Sets the product code.
     *
     * @param string $value
     * @return $this
     */
    public function setCode($value)
    {
        return $this->setParameter('code', $value);
    }

    /**
     * Get the product currency code.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->getParameter('currencyCode');
    }

    /**
     * Sets the product currency code.
     *
     * @param string $value
     * @return $this
     */
    public function setCurrencyCode($value)
    {
        return $this->setParameter('currencyCode', $value);
    }

    /**
     * Get the product description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Sets the product description.
     *
     * @param string $value
     * @return $this
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Sets the product name.
     *
     * @param string $value
     * @return $this
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get the product quantity.
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->getParameter('quantity');
    }

    /**
     * Sets the product quantity.
     *
     * @param string $value
     * @return $this
     */
    public function setQuantity($value)
    {
        return $this->setParameter('quantity', $value);
    }

    /**
     * Get the product risk.
     *
     * @return string
     */
    public function getRisk()
    {
        return $this->getParameter('risk');
    }

    /**
     * Sets the product risk.
     *
     * @param string $value
     * @return $this
     */
    public function setRisk($value)
    {
        return $this->setParameter('risk', $value);
    }

    /**
     * Get the product type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Sets the product type.
     *
     * @param string $value
     * @return $this
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }
}