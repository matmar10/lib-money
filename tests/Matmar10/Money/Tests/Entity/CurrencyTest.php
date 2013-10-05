<?php

namespace Matmar10\Money\Tests\Entity;

use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\SerializerBuilder;
use Matmar10\Money\Entity\Currency;
use PHPUnit_Framework_TestCase as TestCase;

class CurrencyTest extends TestCase
{
    public function setUp()
    {
        // annotations don't auto-load correctly using just psr-0
        // http://stackoverflow.com/questions/13582055/webtestcase-does-not-autoload-route-annotation
        AnnotationRegistry::registerAutoloadNamespaces(array(
            'JMS\\Serializer\\Annotation\\' => dirname(__FILE__) . '/../../../../../vendor/jms/serializer/src/'
        ));
    }

    public function testEquals()
    {
        $usdCode = 'USD';
        $usd = new Currency($usdCode, 5, 2);
        $usd2 = new Currency($usdCode, 5, 2);
        $usd3 = new Currency($usdCode, 7, 2);

        $this->assertTrue($usd->equals($usd2));
        $this->assertTrue($usd2->equals($usd));
        $this->assertFalse($usd->equals($usd3));

        $eurCode = 'EUR';
        $eur = new Currency($eurCode, 5, 2);
        $eur2 = new Currency($eurCode, 5, 2);
        $eur3 = new Currency($eurCode, 6, 2);

        $this->assertTrue($eur->equals($eur2));
        $this->assertTrue($eur2->equals($eur));
        $this->assertFalse($eur->equals($eur3));

        $this->assertFalse($usd->equals($eur));
        $this->assertFalse($eur->equals($usd));
    }
    
    /**
     * @expectedException Matmar10\Money\Exception\InvalidArgumentException
     */
    public function testException()
    {
        $invalidCode = 'USDA';
        $usda = new Currency($invalidCode, 5, 2);
    }

    public function testSerialize()
    {
        
        $serializer = SerializerBuilder::create()->build();

        $usd = new Currency('USD', 5, 2);
        $json = $serializer->serialize($usd, 'json');
        $this->assertEquals('{"currencyCode":"USD","precision":5,"displayPrecision":2,"symbol":""}', $json);

        $cad = new Currency('CAD', 8, 8);
        $json2 = $serializer->serialize($cad, 'json');
        $this->assertEquals('{"currencyCode":"CAD","precision":8,"displayPrecision":8,"symbol":""}', $json2);

    }

    public function testDeserialize()
    {

        $serializer = SerializerBuilder::create()->build();

        $usd = new Currency('USD', 5, 2);
        $json = '{"currencyCode":"USD","precision":5,"displayPrecision":2,"symbol":""}';
        $usdResult = $serializer->deserialize($json, 'Matmar10\Money\Entity\Currency', 'json');
        $this->assertEquals($usd, $usdResult);

        $cad = new Currency('CAD', 8, 8);
        $json2 = '{"currencyCode":"CAD","precision":8,"displayPrecision":8,"symbol":""}';
        $cadResult = $serializer->deserialize($json2, 'Matmar10\Money\Entity\Currency', 'json');
        $this->assertEquals($cad, $cadResult);

    }



    public function test__toString()
    {
        $usd = new Currency('USD', 2, 2, '$');
        $this->assertEquals('USD', $usd->__toString());
        $this->assertEquals('USD', (string)$usd);
    }
}
