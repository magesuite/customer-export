<?php

namespace MageSuite\CustomerExport\Test\Integration\Services\File\Adapter;

use MageSuite\CustomerExport\Services\File\Adapter\FileAdapter;
use PHPUnit\Framework\TestCase;

class FileAdapterTest extends TestCase
{
    /**
     * @var FileAdapter $fileAdapter
     */
    private $fileAdapter;

    public function setUp()
    {
        $objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->fileAdapter = $objectManager->create(FileAdapter::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture default/customerexport/automatic/export_filename export.xml
     */
    public function testWrite()
    {
        $data = 'Foo';

        $filename = $this->fileAdapter->write($data);

        $this->assertFileExists($filename);
        $this->assertSame($data, file_get_contents($filename));
    }
}
