<?php

namespace Matmar10\Money\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\CurrencyPairInterface;

/**
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
class BaseCurrencyPair implements CurrencyPairInterface
{

    /**
     * @Type("Matmar10\Money\Entity\Currency")
     * @SerializedName("fromCurrency")
     */
    protected $fromCurrency;

    /**
     * @Type("Matmar10\Money\Entity\Currency")
     * @SerializedName("toCurrency")
     */
    protected $toCurrency;

    public function __construct(CurrencyInterface $fromCurrency, CurrencyInterface $toCurrency) {
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency = $toCurrency;
    }

    public function setFromCurrency(CurrencyInterface $fromCurrency)
    {
        $this->fromCurrency = $fromCurrency;
    }

    public function getFromCurrency()
    {
        return $this->fromCurrency;
    }

    public function setToCurrency(CurrencyInterface $toCurrency)
    {
        $this->toCurrency = $toCurrency;
    }

    public function getToCurrency()
    {
        return $this->toCurrency;
    }

    /**
     * Compares whether this currency pair equals the supplied currency code
     *
     * @param \Matmar10\Money\Entity\CurrencyPairInterface $currencyPair The currency pair to check against
     * @return boolean
     */
    public function equals(CurrencyPairInterface $currencyPair)
    {
        return self::currencyCodesMatch($this->fromCurrency, $currencyPair->getFromCurrency()) &&
            self::currencyCodesMatch($this->toCurrency, $currencyPair->getToCurrency());
    }

    /**
     * Checks whether the provided currency pair is the opposite of this pair
     *
     * @param \Matmar10\Money\Entity\CurrencyPairInterface $currencyPair The currency pair to check against
     * @return boolean
     */
    public function isInverse(CurrencyPairInterface $currencyPair)
    {
        return self::currencyCodesMatch($this->fromCurrency, $currencyPair->getToCurrency()) &&
            self::currencyCodesMatch($this->toCurrency, $currencyPair->getFromCurrency());
    }

    /**
     * Returns the inverse of the currency pair instance
     *
     * @return \Matmar10\Money\Entity\CurrencyPairInterface
     */
    public function getInverse()
    {
        $className = get_class($this);
        return new $className($this->toCurrency, $this->fromCurrency);
    }

    /**
     * Compares currency codes as plain strings, ignoring precision
     *
     * @param \Matmar10\Money\Entity\CurrencyInterface $currency The currency pair to check against
     * @param \Matmar10\Money\Entity\CurrencyInterface $compareToCurrency The currency pair to check against
     * @return boolean
     */
    static function currencyCodesMatch(CurrencyInterface $currency, CurrencyInterface $compareToCurrency)
    {
        return $currency->getCurrencyCode() === $compareToCurrency->getCurrencyCode();
    }

    /**
     * {inheritDoc}
     */
    public function __toString()
    {
        return (string)$this->getFromCurrency() . ":" . (string)$this->getToCurrency();
    }
}
