<?php

namespace MageSuite\CustomerExport\Test\Integration\Services\Export;

use MageSuite\CustomerExport\Services\Parser\XmlParser;
use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase
{
    /**
     * @var XmlParser $parser
     */
    private $parser;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    private $customerFactory;

    public function setUp()
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->parser = $objectManager->create(XmlParser::class);
        $this->customerFactory = $objectManager->create(\Magento\Customer\Model\ResourceModel\Customer\CollectionFactory::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testParseCustomersToXml()
    {
        $customers = $this->customerFactory->create();

        $xml = $this->parser->parse($customers);

        $this->assertContains('<?xml version="1.0"?>', $xml);
        $this->assertContains('<Firstname>John</Firstname>', $xml);
        $this->assertContains('<Lastname>Smith</Lastname>', $xml);
        $this->assertContains('<Email>customer@example.com</Email>', $xml);
        $this->assertContains('<Addresses/>', $xml);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_address.php
     */
    public function testParseCustomersWithAddressToXml()
    {
        $customers = $this->customerFactory->create();

        $xml = $this->parser->parse($customers);

        $this->assertContains('<Addresses>', $xml);
        $this->assertContains('<Address>', $xml);

        $this->assertContains('<City>CityM</City>', $xml);
        $this->assertContains('<Company>CompanyName</Company>', $xml);
        $this->assertContains('<Country>US</Country>', $xml);
        $this->assertContains('<StreetFull>Green str, 67</StreetFull>', $xml);
        $this->assertContains('<Telephone>3468676</Telephone>', $xml);
        $this->assertContains('<VatId/>', $xml);
    }
}