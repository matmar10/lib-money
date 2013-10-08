<?php

namespace Matmar10\Money\Entity;

use Matmar10\Money\Entity\MoneyInterface;

/**
 * ExchangeRateInterface
 *
 * @package lib-money
 * @author <matthew.mar10@gmail.com>
 */
interface ExchangeRateInterface
{
    /**
     * @abstract
     * @param $multiplier
     * @return null
     */
    public function setMultiplier($multiplier);

    /**
     * Sets the exchange rate multiplier
     *
     * @abstract
     * @return float
     */
    public function getMultiplier();

    /**
     * Get the inverse rate (e.g. 1/rate) corresponding to the reverse currency pair
     *
     * @abstract
     * @return \Matmar10\Money\Entity\ExchangeRateInterface
     */
    public function getInverse();

    /**
     * Converts the provided amount of money; an exception is raised if neither of this rate's currencies match the provided money instance
     *
     * @abstract
     * @param \Matmar10\Money\Entity\MoneyInterface $amount
     * @throws \Matmar10\Money\Exception\InvalidArgumentException
     * @return \Matmar10\Money\Entity\MoneyInterface
     */
    public function convert(MoneyInterface $amount);

    /**
     * {inheritDoc}
     */
    public function __toString();

}
