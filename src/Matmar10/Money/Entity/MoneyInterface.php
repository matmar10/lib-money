<?php

namespace Matmar10\Money\Entity;

use Matmar10\Money\Entity\CurrencyInterface;
use Matmar10\Money\Exception\InvalidArgumentException;

/**
 * MoneyInterface
 *
 * @package lib-money
 * @author <matthew.mar10@gmail.com>
 */
interface MoneyInterface
{

    const ROUND_TO_DISPLAY = 'ROUND_TO_DISPLAY';
    const ROUND_TO_DEFAULT = 'ROUND_TO_DEFAULT';

    /**
     * Sets the currency of this instance
     *
     * @abstract
     * @param CurrencyInterface $currency The currency
     * @returnnull
     */
    public function setCurrency(CurrencyInterface $currency);

    /**
     * Gets the currency
     *
     * @abstract
     * @return \Matmar10\Money\Entity\Currency The currency of this instance
     */
    public function getCurrency();

    /**
     * Gets the scale, e.g. the multiplier to turn amounts into the integer representation
     *
     * @abstract
     * @return integer The scale multiplier
     */
    public function getScale();

    /**
     * Sets the money amount from a float value which is converted to an integer internally
     *
     * @abstract
     * @param float $amountFloat The amount to set as a float
     * @return null
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the amount is neither a float nor an integer
     */
    public function setAmountFloat($amountFloat);

    /**
     * Gets the amount as a float value based on the specified rounding and currency's decimal precision
     *
     * @abstract
     * @param string $roundTo
     * @return float The amount of money as a float primitive
     */
    public function getAmountFloat($roundTo = self::ROUND_TO_DEFAULT);

    /**
     * Sets the money amount from an integer value
     *
     * @abstract
     * @param integer $amountInteger The amount to set as a float
     * @return null
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if either of the precision values are not integers
     */
    public function setAmountInteger($amountInteger);

    /**
     * Gets the amount as an integer value
     *
     * @abstract
     * @return integer The amount as an integer
     */
    public function getAmountInteger();

    /**
     * Gets the amount as a string rounded to the display precision
     *
     * @abstract
     * @return string The amount rounded to the display precision
     */
    public function getAmountDisplay();

    /**
     * Sets the amount from a string or other numeric pars-able amount
     *
     * @abstract
     * @param string|float|integer $amountDisplay The amount to set
     * @return null
     */
    public function setAmountDisplay($amountDisplay);

    /**
     * Adds the provided money amount to the instance amount and
     * returns the result as a new money instance
     *
     * @abstract
     * @param MoneyInterface $money The amount to add to the instance
     * @return \Matmar10\Money\Entity\MoneyInterface The resulting sum
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function add(MoneyInterface $money);

    /**
     * Adds the provided money amount to the instance amount and
     * returns the result as a new money instance
     *
     * @abstract
     * @param MoneyInterface $money The amount to subtract from the instance
     * @return \Matmar10\Money\Entity\MoneyInterface The resulting difference
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function subtract(MoneyInterface $money);

    /**
     * Multiplies the instance by the provided multiplier
     *
     * @abstract
     * @param float|integer $multiplier The amount to multiply the instance by
     * @return \Matmar10\Money\Entity\MoneyInterface The resulting product
     */
    public function multiply($multiplier);

    /**
     * Multiplies the instance by the provided multiplier
     *
     * @abstract
     * @param float|integer $divisor The amount to divide the instance by
     * @return \Matmar10\Money\Entity\MoneyInterface The resulting quotient
     */
    public function divide($divisor);

    /**
     * Compares the provided money instance to the instance to check if the amount and currencies are equal
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money instance to compare against
     * @return boolean Whether the provided currencies are equivalent in amount and currency
     */
    public function isSameCurrency(MoneyInterface $rightHandValue);

    /**
     * Asserts that the provided money is equal to this instance
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money instance to compare against
     * @return boolean Whether the provided currencies are equivalent in amount and currency
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function assertSameCurrency(MoneyInterface $rightHandValue);

    /**
     * Checks if the provided money amount is less than this instance
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money amount to compare this instance against
     * @return boolean Whether the provided money amount is less than the instance
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function isLess(MoneyInterface $rightHandValue);

    /**
     * Checks if the provided money amount is greater than this instance
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money amount to compare this instance against
     * @return boolean Whether the provided money amount is greater than the instance
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function isGreater(MoneyInterface $rightHandValue);

    /**
     * Checks if the provided money amount is equal to this instance
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money amount to compare this instance against
     * @return boolean Whether the provided money amount is equal to the instance
     */
    public function isEqual(MoneyInterface $rightHandValue);

    /**
     * Checks if the provided money amount is less than or equal than this instance
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money amount to compare this instance against
     * @return boolean Whether the provided money amount is less than or equal than the instance
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function isLessOrEqual(MoneyInterface $rightHandValue);

    /**
     * Checks if the provided money amount is greater than or equal than this instance
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money amount to compare this instance against
     * @return boolean Whether the provided money amount is greater than or equal than the instance
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function isGreaterOrEqual(MoneyInterface $rightHandValue);

    /**
     * Compares the value of the amounts to check if the provided amount is less, equal, or greater than
     *
     * @abstract
     * @param MoneyInterface $rightHandValue The money amount to compare this instance against
     * @return integer Returns -1 if the amount is less, 0 if the amount is equal, or 1 if the amount is greater
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if the currencies don't match
     */
    public function compare(MoneyInterface $rightHandValue);

    /**
     * Returns whether the amount is zero
     *
     * @abstract
     * @return boolean Whether the amount is zero
     */
    public function isZero();

    /**
     * Returns whether the amount is greater than zero
     *
     * @abstract
     * @return boolean Whether the amount is greater than zero
     */
    public function isPositive();

    /**
     * Returns whether the amount is less than than zero
     *
     * @abstract
     * @return boolean Whether the amount is less than than zero
     */
    public function isNegative();

    /**
     * Allocates the amount into the specified proportions
     *
     * @abstract
     * @param array $ratios An array of rations to allocate the money into, e.g. array(1,1,1) allocates into three equal parts
     * @param integer|string $roundToPrecision An integer of decimal places to round to or the method of rounding either to display precision or calculation precision
     * @return array The array of allocated portions
     */
    public function allocate(array $ratios, $roundToPrecision = self::ROUND_TO_DEFAULT);

    /**
     * {inheritDoc}
     */
    public function __toString();
}
