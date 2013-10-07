<?php

namespace Matmar10\Money\Tests\Entity;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Matmar10\Money\Entity\CurrencyPair;
use Matmar10\Money\Entity\Currency;
use Matmar10\Money\Entity\Money;
use JMS\Serializer\SerializerBuilder;
use PHPUnit_Framework_TestCase as TestCase;

class CurrencyPairTest extends TestCase
{
    protected $usd;
    protected $eur;
    protected $gbp;
    protected $usdToEur;
    protected $usdToGbp;

    public function setUp()
    {
        AnnotationRegistry::registerAutoloadNamespaces(array(
            'JMS\\Serializer\\Annotation' => __DIR__ . '/../../../../../vendor/jms/serializer/src/'
        ));
        $this->usd = new Currency('USD', 5, 2);
        $this->eur = new Currency('EUR', 5, 2);
        $this->gbp = new Currency('GBP', 5, 2);
        $this->usdToEur = new CurrencyPair($this->usd, $this->eur);
        $this->usdToGbp = new CurrencyPair($this->usd, $this->gbp);
    }

    public function testEquals()
    {
        $usdToEur = clone $this->usdToEur;
        $usdToGbp = clone $this->usdToGbp;
        $this->assertTrue($usdToEur->equals($this->usdToEur));
        $this->assertFalse($usdToGbp->equals($this->usdToEur));
    }

    public function testIsInverse()
    {
        $eurToUsd = new CurrencyPair($this->eur, $this->usd);
        $this->assertTrue($eurToUsd->isInverse($this->usdToEur));
        $this->assertFalse($this->usdToGbp->isInverse($this->usdToEur));
    }

    public function testGetInverse()
    {
        $eurToUsd = new CurrencyPair($this->eur, $this->usd);
        $this->assertEquals($eurToUsd, $this->usdToEur->getInverse());
    }

    public function testCurrencyCodesMatch()
    {
        $this->assertTrue(CurrencyPair::currencyCodesMatch($this->usd, $this->usd));
        $this->assertFalse(CurrencyPair::currencyCodesMatch($this->usd, $this->gbp));
    }

    public function test__toString()
    {
        $this->assertEquals('USD:EUR', $this->usdToEur->__toString());
        $this->assertEquals('USD:EUR', (string)$this->usdToEur);
        $this->assertEquals('USD:GBP', $this->usdToGbp->__toString());
        $this->assertEquals('USD:GBP', (string)$this->usdToGbp);
    }

    public function testSerialize()
    {
        $serializer = SerializerBuilder::create()->build();

        $usd = new Currency('USD', 5, 2);
        $btc = new Currency('BTC', 8, 8);
        $pair = new CurrencyPair($usd, $btc);
        $json = $serializer->serialize($pair, 'json');
        $this->assertEquals('{"fromCurrency":{"currencyCode":"USD","precision":5,"displayPrecision":2,"symbol":""},"toCurrency":{"currencyCode":"BTC","precision":8,"displayPrecision":8,"symbol":""}}', $json);
    }

    public function testDeserialize()
    {
        $serializer = SerializerBuilder::create()->build();

        $usd = new Currency('USD', 5, 2);
        $btc = new Currency('BTC', 8, 8);
        $expectedPair = new CurrencyPair($usd, $btc);
        $pair = $serializer->deserialize('{"fromCurrency":{"currencyCode":"USD","precision":5,"displayPrecision":2,"symbol":""},"toCurrency":{"currencyCode":"BTC","precision":8,"displayPrecision":8,"symbol":""}}', 'Matmar10\Money\Entity\CurrencyPair', 'json');
        $this->assertEquals($expectedPair, $pair);
    }

}