<?php

namespace Matmar10\Money\Entity;

/**
 * CurrencyInterface
 *
 * @package lib-money
 * @author <matthew.mar10@gmail.com>
 */
interface CurrencyInterface
{

    const CURRENCY_CODE_LENGTH = 3;

    /**
     * Sets the currency code
     *
     * @abstract
     * @param string $currencyCode The currency code
     * @return null
     */
    public function setCurrencyCode($currencyCode);

    /**
     * Gets the currency code
     *
     * @abstract
     * @return string
     */
    public function getCurrencyCode();

    /**
     * Sets the decimal precision for calculation
     *
     * @abstract
     * @param integer $precision The decimal precision
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the provided $precision is not an integer
     * @return null
     */
    public function setPrecision($precision);

    /**
     * Gets the calculation precision
     *
     * @abstract
     * @return integer
     */
    public function getPrecision();

    /**
     * Sets the display precision
     *
     * @param integer $precision The display precision
     * @abstract
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the provided $precision is not an integer
     * @return null
     */
    public function setDisplayPrecision($precision);

    /**
     * Gets the display precision
     *
     * @abstract
     * @return integer
     */
    public function getDisplayPrecision();

    /**
     * Gets the display precision
     *
     * @param CurrencyInterface $currency The instance to compare against for equality
     * @abstract
     * @return boolean
     */
    public function equals(CurrencyInterface $currency);

    /**
     * Sets the currency symbol
     *
     * @param string $symbol The currency symbol
     * @abstract
     * @return null
     */
    public function setSymbol($symbol);

    /**
     * Gets the currency symbol
     *
     * @abstract
     * @return string
     */
    public function getSymbol();

    /**
     * {inheritDoc}
     */
    public function __toString();

}
