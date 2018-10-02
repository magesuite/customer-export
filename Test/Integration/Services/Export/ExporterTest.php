<?php

namespace MageSuite\CustomerExport\Test\Integration\Services\Export;

use MageSuite\CustomerExport\Services\Export\Exporter;
use PHPUnit\Framework\TestCase;

class ExporterTest extends TestCase
{
    /**
     * @var Exporter $exporter
     */
    private $exporter;

    public function setUp()
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->exporter = $objectManager->create(Exporter::class);
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
}