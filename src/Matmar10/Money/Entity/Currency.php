<?php

namespace Matmar10\Money\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Matmar10\Money\Entity\CurrencyInterface;
use Matmar10\Money\Exception\InvalidArgumentException;

/**
 * Currency
 * @package lib-money
 * @author <matthew.mar10@gmail.com>
 *
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
class Currency implements CurrencyInterface
{

    /**
     * @Type("string")
     * @SerializedName("currencyCode")
     */
    protected $currencyCode;

    /**
     * @Type("integer")
     */
    protected $precision;
    
    /**
     * @Type("integer")
     * @SerializedName("displayPrecision")
     */
    protected $displayPrecision;

    /**
     * @Type("string")
     */
    protected $symbol;

    /**
     * Creates a new currency instance
     *
     * @param string $currencyCode The three digit currency code (ISO-4217)
     * @param integer $precision The precision used for calculation
     * @param integer $displayPrecision The precision used for when amounts are displayed
     * @param string $symbol OPTIONAL The currency symbol
     * @throws \Matmar10\Money\Exception\InvalidArgumentException if either of the precision values are not integers
     */
    public function __construct($currencyCode, $precision, $displayPrecision, $symbol = '')
    {
        $this->setCurrencyCode($currencyCode);
        if(!is_int($precision)) {
            throw new InvalidArgumentException(sprintf('Invalid precision %s: must be of type integer', $precision));
        }
        $this->precision = $precision;
        if(!is_int($displayPrecision)) {
            throw new InvalidArgumentException(sprintf('Invalid display precision %s: must be of type integer', $displayPrecision));
        }
        $this->displayPrecision = $displayPrecision;
        $this->symbol = $symbol;
    }

    /**
     * {inheritDoc}
     */
    public function setCurrencyCode($currencyCode)
    {
        if(strlen($currencyCode) !== self::CURRENCY_CODE_LENGTH) {
            throw new InvalidArgumentException("Invalid currency code '$currencyCode' specified: currency codes must be " . self::CURRENCY_CODE_LENGTH .  "characters in length.");
        }
        $this->currencyCode = $currencyCode;
    }

    /**
     * {inheritDoc}
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * {inheritDoc}
     */
    public function setPrecision($precision)
    {
        if(!is_int($precision)) {
            throw new InvalidArgumentException(sprintf('Invalid precision %s: must be of type integer', $precision));
        }
        $this->precision = $precision;
    }

    /**
     * {inheritDoc}
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * {inheritDoc}
     */
    public function setDisplayPrecision($precision)
    {
        if(!is_int($precision)) {
            throw new InvalidArgumentException(sprintf('Invalid display precision %s: must be of type integer', $precision));
        }
        $this->displayPrecision = $precision;
    }

    /**
     * {inheritDoc}
     */
    public function getDisplayPrecision()
    {
        return $this->displayPrecision;
    }

    /**
     * {inheritDoc}
     */
    public function equals(CurrencyInterface $currency) {
        return $this->currencyCode === $currency->getCurrencyCode() &&
                $this->precision === $currency->getPrecision() &&
                $this->displayPrecision === $currency->getDisplayPrecision();
    }

    /**
     * {inheritDoc}
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * {inheritDoc}
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * {inheritDoc}
     */
    public function __toString() {
        return $this->getCurrencyCode();
    }
}
