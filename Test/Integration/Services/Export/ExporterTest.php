<?php

namespace MageSuite\CustomerExport\Test\Integration\Services\Export;

class ExporterTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Exporter $exporter
     */
    private $exporter;

    /**
     * @var FileWriter $fileWriter
     */
    private $fileWriter;

    /**
     * @var CustomerRepository $customerRepository
     */
    private $customerRepository;

    public function setUp()
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->exporter = $objectManager->create(\MageSuite\CustomerExport\Services\Export\Exporter::class);
        $this->fileWriter = $objectManager->create(\MageSuite\CustomerExport\Services\File\Writer\FileWriter::class);
        $this->customerRepository = $objectManager->create(\Magento\Customer\Model\ResourceModel\CustomerRepository::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     */
    public function testPrepareCustomerCollectionWithLatelyUpdatedCustomer()
    {
        $customers = $this->exporter->prepareCustomersCollection();

        $this->assertCount(1, $customers);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Customer/_files/customer_address.php
     */
    public function testExportCustomersToXmlFile()
    {
        $customer = $this->customerRepository->get('customer@example.com');

        $this->exporter->export('xml', 'file', 'customer_export.xml');

        $filePath = $this->fileWriter->prepareWritePath('customer_export.xml');

        $xml = new \DOMDocument();
        $xml->load($filePath);

        $this->assertCount(1, $xml->getElementsByTagName('Customer'));

        $this->assertCount(1, $xml->getElementsByTagName('Id'));
        $this->assertSame('1', $xml->getElementsByTagName('Id')->item(0)->textContent);

        $this->assertCount(2, $xml->getElementsByTagName('Firstname'));
        $this->assertSame('John', $xml->getElementsByTagName('Firstname')->item(0)->textContent);

        $this->assertCount(1, $xml->getElementsByTagName('Email'));
        $this->assertSame('customer@example.com', $xml->getElementsByTagName('Email')->item(0)->textContent);

        $this->assertCount(1, $xml->getElementsByTagName('CreatedAt'));
        $this->assertRegExp(
            '/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/',
            $xml->getElementsByTagName('CreatedAt')->item(0)->textContent
        );

        $this->assertCount(1, $xml->getElementsByTagName('Address'));

        $this->assertCount(1, $xml->getElementsByTagName('City'));
        $this->assertSame('CityM', $xml->getElementsByTagName('City')->item(0)->textContent);

        $this->assertCount(1, $xml->getElementsByTagName('Company'));
        $this->assertSame('CompanyName', $xml->getElementsByTagName('Company')->item(0)->textContent);

        $this->assertCount(1, $xml->getElementsByTagName('VatId'));
        $this->assertSame('', $xml->getElementsByTagName('VatId')->item(0)->textContent);

    }
}