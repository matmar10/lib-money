<?php

namespace Matmar10\Money\Entity;

use Matmar10\Money\Entity\CurrencyInterface;
use Matmar10\Money\Exception\InvalidArgumentException;

interface MoneyInterface
{

    const ROUND_TO_DISPLAY = 'ROUND_TO_DISPLAY';
    const ROUND_TO_DEFAULT = 'ROUND_TO_DEFAULT';

    public function setCurrency(CurrencyInterface $currency);

    public function getCurrency();

    public function getScale();

    public function setAmountFloat($amountFloat);

    public function getAmountFloat($roundTo = self::ROUND_TO_DEFAULT);

    public function setAmountInteger($amountInteger);

    public function getAmountInteger();

    public function getAmountDisplay();

    public function setAmountDisplay($amountDisplay);

    public function add(MoneyInterface $money);

    public function subtract(MoneyInterface $money);

    public function multiply($multiplier);

    public function divide($divisor);

    public function isSameCurrency(MoneyInterface $rightHandValue);

    public function assertSameCurrency(MoneyInterface $rightHandValue);

    public function isLess(MoneyInterface $rightHandValue);

    public function isGreater(MoneyInterface $rightHandValue);

    public function isEqual(MoneyInterface $rightHandValue);

    public function isLessOrEqual(MoneyInterface $rightHandValue);

    public function isGreaterOrEqual(MoneyInterface $rightHandValue);

    public function compare(MoneyInterface $rightHandValue);

    public function isZero();
    
    public function isPositive();

    public function isNegative();

    public function allocate(array $ratios, $roundToPrecision = self::ROUND_TO_DEFAULT);

    public function __toString();
}
