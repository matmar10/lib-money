<?php

namespace Matmar10\Money\Entity;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\ReadOnly;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Matmar10\Money\Exception\InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Money
 *
 * @package lib-money
 * @author <matthew.mar10@gmail.com>
 *
 * @AccessType("public_method")
 * @ExclusionPolicy("none")
 * @ORM\Embeddable()
 */
class Money implements MoneyInterface
{

    /**
     * @var \Matmar10\Money\Entity\CurrencyInterface
     *
     * @Type("Matmar10\Money\Entity\Currency")
     * @ORM\Column(name="currency", type="currency", nullable=false)
     */
    protected $currency;

    /**
     * @var integer
     *
     * @Type("integer")
     * @ReadOnly
     */
    protected $scale;

    /**
     * @var integer
     *
     * @Type("integer")
     * @SerializedName("amountInteger")
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    protected $amountInteger = 0;

    /**
     * @var float
     *
     * @Type("double")
     * @SerializedName("amountFloat")
     */
    protected $amountFloat;

    /**
     * @var string
     *
     * @Type("string")
     * @SerializedName("amountDisplay")
     */
    protected $amountDisplay;

    /**
     * @param CurrencyInterface     $currency
     * @param int|float|string|null $amount
     *
     * @return Money
     */
    public static function create(CurrencyInterface $currency, $amount = null)
    {
        return new static($currency, $amount);
    }

    /**
     * @param CurrencyInterface     $currency
     * @param int|float|string|null $amount
     */
    public function __construct(CurrencyInterface $currency, $amount = null)
    {
        $this->setCurrency($currency);
        if(!is_null($amount)) {
            switch (true) {
                case is_float($amount):
                    $this->setAmountFloat($amount);
                    break;
                case is_int($amount):
                    $this->setAmountInteger($amount);
                    break;
                case is_string($amount);
                    $this->setAmountDisplay($amount);
                    break;
                default:
                    throw new \InvalidArgumentException('Amount should be INT|FLOAT|STRING|NULL');
                    break;
            }
        }
    }

    public function setCurrency(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getScale()
    {
        return $this->scale ?: $this->scale = bcpow(10, $this->currency->getPrecision(), 0);
    }

    public function setAmountFloat($amountFloat)
    {
        $this->amountInteger = bcmul($amountFloat, $this->getScale(), 0);
    }

    public function getAmountFloat($roundTo = self::ROUND_TO_DEFAULT)
    {
        $scaled = bcdiv($this->amountInteger, $this->getScale(), $this->currency->getPrecision());

        if(self::ROUND_TO_DEFAULT === $roundTo) {
            $rounding = $this->currency->getPrecision();
        } else if(self::ROUND_TO_DISPLAY === $roundTo) {
            $rounding = $this->currency->getDisplayPrecision();
        } else {
            $rounding = $this->currency->getPrecision() + $roundTo;
        }

        return round($scaled, $rounding);
    }

    public function setAmountInteger($amountInteger)
    {
        $this->amountInteger = $amountInteger;
    }

    public function getAmountInteger()
    {
        return (integer)$this->amountInteger;
    }

    public function getAmountDisplay()
    {
        $decimals = $this->getCurrency()->getDisplayPrecision();
        $formatter = "%01.{$decimals}f";
        return sprintf($formatter, $this->getAmountFloat());
    }

    public function setAmountDisplay($amountDisplay)
    {
        $this->setAmountFloat((float)$amountDisplay);
    }

    public function add(MoneyInterface $money)
    {
        $this->assertSameCurrency($money);
        $newMoney = new Money($this->getCurrency());
        $newAmountInteger = $this->getAmountInteger() + $money->getAmountInteger();
        $newMoney->setAmountInteger($newAmountInteger);
        return $newMoney;
    }

    public function subtract(MoneyInterface $money)
    {
        $this->assertSameCurrency($money);
        $newMoney = new Money($this->getCurrency());
        $newAmountInteger = $this->getAmountInteger() - $money->getAmountInteger();
        $newMoney->setAmountInteger($newAmountInteger);
        return $newMoney;
    }

    public function multiply($multiplier)
    {
        $newMoney = new Money($this->getCurrency());
        $newAmountInteger = bcmul($this->getAmountInteger(), $multiplier, 0);
        $newMoney->setAmountInteger($newAmountInteger);
        return $newMoney;
    }

    public function divide($divisor)
    {
        $newMoney = new Money($this->getCurrency());
        $newAmountInteger = bcdiv($this->getAmountInteger(), $divisor, 0);
        $newMoney->setAmountInteger($newAmountInteger);
        return $newMoney;
    }

    public function isSameCurrency(MoneyInterface $rightHandValue)
    {
        return $this->currency->equals($rightHandValue->getCurrency());
    }

    public function assertSameCurrency(MoneyInterface $rightHandValue)
    {
        if(!$this->isSameCurrency($rightHandValue)) {
            $msg = "Different currencies provided: Money object of Currency type %s with precision %n and display precision %n expected.";
            throw new InvalidArgumentException(sprintf($msg, $this->currency->getCurrencyCode(), $this->currency->getPrecision(), $this->currency->getDisplayPrecision()));
        }
    }

    public function isLess(MoneyInterface $rightHandValue)
    {
        $this->assertSameCurrency($rightHandValue);
        return (integer)$this->amountInteger < $rightHandValue->getAmountInteger();
    }

    public function isGreater(MoneyInterface $rightHandValue)
    {
        $this->assertSameCurrency($rightHandValue);
        return (integer)$this->amountInteger > $rightHandValue->getAmountInteger();
    }

    public function isEqual(MoneyInterface $rightHandValue)
    {
        $this->assertSameCurrency($rightHandValue);
        return (integer)$this->amountInteger === $rightHandValue->getAmountInteger();
    }

    public function isLessOrEqual(MoneyInterface $rightHandValue)
    {
        $this->assertSameCurrency($rightHandValue);
        return (integer)$this->amountInteger <= $rightHandValue->getAmountInteger();
    }

    public function isGreaterOrEqual(MoneyInterface $rightHandValue)
    {
        $this->assertSameCurrency($rightHandValue);
        return (integer)$this->amountInteger >= $rightHandValue->getAmountInteger();
    }

    public function compare(MoneyInterface $rightHandValue)
    {
        $this->assertSameCurrency($rightHandValue);
        $otherAmount = $rightHandValue->getAmountInteger();

        if((integer)$this->amountInteger < $otherAmount) {
            return -1;
        }

        if((integer)$this->amountInteger === $otherAmount) {
            return 0;
        }
        // $this->amountInteger > $otherAmount
        return 1;
    }

    public function isZero()
    {
        return $this->amountInteger == 0;
    }
    
    public function isPositive()
    {
        return $this->amountInteger > 0;
    }

    public function isNegative()
    {
        return $this->amountInteger < 0;
    }

    public function allocate(array $ratios, $roundToPrecision = self::ROUND_TO_DEFAULT)
    {
        $total = array_sum($ratios);
        if(!count($ratios) || !$total) {
            throw new InvalidArgumentException('Invalid ratios specified: at least one ore more positive ratios must be provided.');
        }

        if(is_integer($roundToPrecision)) {
            $precision = $roundToPrecision;
        } else {
            $precision = (self::ROUND_TO_DEFAULT === $roundToPrecision) ?
                $this->currency->getPrecision() : $this->currency->getDisplayPrecision();
        }

        $currency = clone $this->currency;
        $currency->setPrecision($precision);
        $currency->setDisplayPrecision($this->currency->getDisplayPrecision());

        $amount = new Money($currency);
        $amount->setAmountFloat($this->getAmountFloat());
        $remainder = clone $amount;

        $results = array();
        $increment = $amount->getScale() / pow(10, $currency->getPrecision());

        foreach ($ratios as $ratio) {
            if($ratio < 0) {
                throw new InvalidArgumentException("Invalid share ratio '" . $ratio . "' supplied: ratios may not be negative amounts.");
            }
            $share = $amount->multiply($ratio)->divide($total);
            $results[] = $share;
            $remainder = $remainder->subtract($share);
        }

        for ($i = 0; $remainder->isPositive(); $i++) {
            $amountInteger = $results[$i]->getAmountInteger();
            $results[$i]->setAmountInteger($amountInteger + $increment);
            $increment = $amount->getScale() / pow(10, $amount->currency->getPrecision());
            $remainderAmountInteger = $remainder->getAmountInteger();
            $remainder->setAmountInteger($remainderAmountInteger - $increment);
        }

        $convertedResults = array();
        foreach($results as $result) {
            /**
             * @var $result Money
             */
            $converted = new Money($this->currency);
            $converted->setAmountFloat($result->getAmountFloat());
            $convertedResults[] = $converted;
        }

        return $convertedResults;
    }

    public function __toString()
    {
        return $this->getAmountDisplay() . ' ' . $this->currency;
    }
}
