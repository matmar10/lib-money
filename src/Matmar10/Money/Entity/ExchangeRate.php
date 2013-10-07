<?php

namespace Matmar10\Money\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Matmar10\Money\Entity\CurrencyPair;
use Matmar10\Money\Entity\CurrencyInterface;
use Matmar10\Money\Entity\ExchangeRateInterface;
use Matmar10\Money\Entity\Money;
use Matmar10\Money\Exception\InvalidArgumentException;

/**
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 */
class ExchangeRate extends CurrencyPair implements ExchangeRateInterface
{
    /**
     * @var float
     *
     * @Type("double")
     */
    protected $multiplier;

    public function __construct(CurrencyInterface $fromCurrency, CurrencyInterface $toCurrency, $multiplier) {
        parent::__construct($fromCurrency, $toCurrency);
        $this->multiplier = $multiplier;
    }

    /**
     * {inheritDoc}
     */
    public function setMultiplier($multiplier)
    {
        $this->multiplier = $multiplier;
    }

    /**
     * {inheritDoc}
     */
    public function getMultiplier()
    {
        return $this->multiplier;
    }

    /**
     * {inheritDoc}
     */
    public function getInverse()
    {
        $className = get_class($this);
        if(!is_null($this->multiplier)) {
            return new $className($this->toCurrency, $this->fromCurrency, 1 / $this->multiplier);
        }
        return new $className($this->toCurrency, $this->fromCurrency);
    }

    /**
     * {inheritDoc}
     */
    public function convert(MoneyInterface $amount)
    {
        if($amount->getCurrency()->equals($this->getFromCurrency())) {
            $newAmount = $amount->multiply($this->getMultiplier());
            $newMoney = new Money($this->toCurrency);
            $newMoney->setAmountFloat($newAmount->getAmountFloat());
            return $newMoney;
        }

        if($amount->getCurrency()->equals($this->getToCurrency())) {
            $newAmount = $amount->divide($this->getMultiplier());
            $newMoney = new Money($this->fromCurrency);
            $newMoney->setAmountFloat($newAmount->getAmountFloat());
            return $newMoney;
        }

        throw new InvalidArgumentException("Cannot convert from " . $amount->getCurrency()->getCurrencyCode() .
            " using CurrencyRate of " .
            $this->getFromCurrency()->getCurrencyCode() .
            " to " .
            $this->getToCurrency()->getCurrencyCode() .
            ": CurrencyRate must include the base currency " .
            $amount->getCurrency()->getCurrencyCode()
        );
    }

    /**
     * {inheritDoc}
     */
    public function __toString()
    {
        return (string)$this->getFromCurrency() . ':' . (string)$this->getToCurrency() . '@' . $this->multiplier;
    }
}
