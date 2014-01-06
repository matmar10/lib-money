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
 * ExchangeRate
 *
 * @package lib-money
 * @author <matthew.mar10@gmail.com>
 *
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
            $totalPrecision = $this->fromCurrency->getPrecision() + $this->toCurrency->getPrecision();
            $targetPrecision = $this->toCurrency->getPrecision();
            $newAmount = bcmul((string)$amount->getAmountFloat(), (string)$this->getMultiplier(), $totalPrecision);
            $roundedAmount = round($newAmount, $targetPrecision);
            $newMoney = new Money($this->toCurrency);
            $newMoney->setAmountFloat($roundedAmount);
            return $newMoney;
        }

        // rate represents inverse, so treat "from" and "to" reversed
        if($amount->getCurrency()->equals($this->getToCurrency())) {
            $totalPrecision = $this->fromCurrency->getPrecision() + $this->toCurrency->getPrecision();
            $targetPrecision = $this->fromCurrency->getPrecision();
            $newAmount = bcdiv((string)$amount->getAmountFloat(), (string)$this->getMultiplier(), $totalPrecision);
            $roundedAmount = round($newAmount, $targetPrecision);
            $newMoney = new Money($this->fromCurrency);
            $newMoney->setAmountFloat($roundedAmount);
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
