<?php

namespace Matmar10\Money\Tests\Entity;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Matmar10\Money\Entity\ExchangeRate;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\Money;
use JMS\Serializer\SerializerBuilder;
use PHPUnit_Framework_TestCase as TestCase;

class ExchangeRateTest extends TestCase
{
    protected $usdCode;
    protected $usd;
    protected $usdMoney;
    protected $eurCode;
    protected $eur;
    protected $eurMoney;
    protected $rate;

    public function setUp()
    {
        AnnotationRegistry::registerAutoloadNamespaces(array(
            'JMS\\Serializer\\Annotation' => __DIR__ . '/../../../../../vendor/jms/serializer/src/'
        ));
        $this->usd = new Currency('USD', 5, 2);
        $this->usdMoney = new Money($this->usd);

        $this->eur = new Currency('EUR', 5, 2);
        $this->eurMoney = new Money($this->eur);

        $this->rate = new ExchangeRate($this->usd, $this->eur, 1.5);
    }

    public function testConvert()
    {
        $usd = clone $this->usdMoney;
        $usd->setAmountFloat(10);

        $rate = clone $this->rate;

        $eur = clone $this->eurMoney;
        $eur->setAmountFloat(15);

        $this->assertEquals($eur, $rate->convert($usd));
        $this->assertEquals($usd, $rate->convert($eur));
    }

    /**
     * @expectedException Matmar10\Money\Exception\InvalidArgumentException
     */
    public function testCannotConvertMismatchedCurrency()
    {
        $jpn = new Currency('JPY', 5, 2);
        $jpnAmount = new Money($jpn);
        $jpnAmount->setAmountFloat(100);

        $this->rate->convert($jpnAmount);
    }


    public function testSerialize()
    {

        $serializer = SerializerBuilder::create()->build();

        $usd = new Currency('USD', 5, 2);
        $btc = new Currency('BTC', 8, 8);
        $pair = new ExchangeRate($usd, $btc, 130);
        $json = $serializer->serialize($pair, 'json');
        $this->assertEquals('{"fromCurrency":{"currencyCode":"USD","precision":5,"displayPrecision":2,"symbol":""},"toCurrency":{"currencyCode":"BTC","precision":8,"displayPrecision":8,"symbol":""},"multiplier":130}', $json);
    }

    public function testDeserialize()
    {

        $serializer = SerializerBuilder::create()->build();

        $usd = new Currency('USD', 5, 2);
        $btc = new Currency('BTC', 8, 8);
        $expectedPair = new ExchangeRate($usd, $btc, 130);
        $pair = $serializer->deserialize('{"fromCurrency":{"currencyCode":"USD","precision":5,"displayPrecision":2,"symbol":""},"toCurrency":{"currencyCode":"BTC","precision":8,"displayPrecision":8,"symbol":""},"multiplier":130}', 'Matmar10\Money\Entity\ExchangeRate', 'json');
        $this->assertEquals($expectedPair, $pair);

    }

    public function test__toString()
    {
        $btc = new Currency('BTC', 8, 8);
        $usd = new Currency('USD', 2, 2, '$');
        $btcToUsd = new ExchangeRate($btc, $usd, 130);

        $this->assertEquals('BTC:USD@130', $btcToUsd->__toString());
        $this->assertEquals('BTC:USD@130', (string)$btcToUsd);
    }
}