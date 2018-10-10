<?php

namespace MageSuite\CustomerExport\Test\Integration\Services\Export;

use Magento\Customer\Model\Data\Customer;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use MageSuite\CustomerExport\Services\Export\Exporter;
use MageSuite\CustomerExport\Services\File\Writer\FileWriter;
use PHPUnit\Framework\TestCase;

class ExporterTest extends TestCase
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

        $this->exporter = $objectManager->create(Exporter::class);
        $this->fileWriter = $objectManager->create(FileWriter::class);
        $this->customerRepository = $objectManager->create(CustomerRepository::class);
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
        $xmlData = $this->prepareExpectedXml($customer);

        $this->exporter->export('xml', 'file', 'customer_export.xml');

        $filePath = $this->fileWriter->prepareWritePath('customer_export.xml');
        $fileData = file_get_contents($filePath);

        $this->assertFileExists($filePath);
        $this->assertSame($xmlData, $fileData);
    }

    private function prepareExpectedXml(Customer $customer)
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->setIndent(true);

        $xml->startElement('Customers');
            $xml->startElement('Customer');
                $xml->startElement('Id');
                    $xml->text('1');
                $xml->endElement();
                $xml->startElement('Firstname');
                    $xml->text('John');
                $xml->endElement();
                $xml->startElement('Lastname');
                    $xml->text('Smith');
                $xml->endElement();
                $xml->startElement('Email');
                    $xml->text('customer@example.com');
                $xml->endElement();
                $xml->startElement('CreatedAt');
                    $xml->text($customer->getCreatedAt());
                $xml->endElement();
                $xml->startElement('UpdatedAt');
                    $xml->text($customer->getUpdatedAt());
                $xml->endElement();
                $xml->startElement('Addresses');
                    $xml->startElement('Address');
                        $xml->startElement('Firstname');
                            $xml->text('John');
                        $xml->endElement();
                        $xml->startElement('Lastname');
                            $xml->text('Smith');
                        $xml->endElement();
                        $xml->startElement('City');
                            $xml->text('CityM');
                        $xml->endElement();
                        $xml->startElement('Company');
                            $xml->text('CompanyName');
                        $xml->endElement();
                        $xml->startElement('Country');
                            $xml->text('US');
                        $xml->endElement();
                        $xml->startElement('Postcode');
                            $xml->text('75477');
                        $xml->endElement();
                        $xml->startElement('Region');
                         $xml->text('Alabama');
                        $xml->endElement();
                        $xml->startElement('StreetFull');
                            $xml->text('Green str, 67');
                        $xml->endElement();
                        $xml->startElement('Telephone');
                            $xml->text('3468676');
                        $xml->endElement();
                        $xml->startElement('VatId');
                            $xml->text('');
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
            $xml->endElement();
        $xml->endElement();

        return $xml->flush();
    }
}