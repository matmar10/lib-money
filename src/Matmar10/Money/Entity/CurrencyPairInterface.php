<?php

namespace Matmar10\Money\Entity;

use Matmar10\Money\Entity\CurrencyInterface;

interface CurrencyPairInterface
{

    public function setFromCurrency(CurrencyInterface $fromCurrency);
    public function getFromCurrency();
    public function setToCurrency(CurrencyInterface $toCurrency);
    public function getToCurrency();

    /**
     * Compares whether this currency pair equals the supplied currency code
     *
     * @abstract
     * @param \Matmar10\Money\Entity\CurrencyPairInterface $currencyPair The currency pair to check against
     * @return boolean
     */
    public function equals(CurrencyPairInterface $currencyPair);

    /**
     * Checks whether the provided currency pair is the opposite of this pair
     *
     * @abstract
     * @param \Matmar10\Money\Entity\CurrencyPairInterface $currencyPair The currency pair to check against
     * @return boolean
     */
    public function isInverse(CurrencyPairInterface $currencyPair);

    /**
     * Returns the inverted representation of the current CurrencyPairInterface instance
     *
     * @abstract
     * @return \Matmar10\Money\Entity\CurrencyPairInterface
     */
    public function getInverse();

    /**
     * Compare currency pairs as plain strings, ignoring precision
     *
     * @abstract
     * @param \Matmar10\Money\Entity\CurrencyInterface $currency A currency instance
     * @param \Matmar10\Money\Entity\CurrencyInterface $compareToCurrency The currency instance to check for equality against
     * @return boolean
     */
    static function currencyCodesMatch(CurrencyInterface $currency, CurrencyInterface $compareToCurrency);

    /**
     * Returns a stringified representation of the pair (e.g. "BTC:USD")
     *
     * @abstract
     * @return string
     */
    public function __toString();

}