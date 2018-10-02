<?php

namespace MageSuite\CustomerExport\Services\Export;

use MageSuite\CustomerExport\Services\File\Adapter\XMLWriter;
use MageSuite\CustomerExport\Services\File\WriterFactory;
use MageSuite\CustomerExport\Services\Parser\ParserFactory;

class Exporter
{
    /**
     * @var ParserFactory $parserFactory
     */
    protected $parserFactory;

    /**
     * @var WriterFactory $writerFactory
     */
    protected $writerFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * Exporter constructor.
     * @param ParserFactory $parserFactory
     * @param WriterFactory $writerFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory
     */
    public function __construct(
        ParserFactory $parserFactory,
        WriterFactory $writerFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->parserFactory = $parserFactory;
        $this->writerFactory = $writerFactory;
        $this->customerFactory = $customerFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return \Magento\Customer\Model\ResourceModel\Customer\Collection
     */
    public function prepareCustomersCollection()
    {
        $customers = $this->customerFactory->create();

        $date = new \DateTime('-1 day');
        $date = $date->format('Y-m-d H:i:s');

        $customers->addFieldToFilter('updated_at', ['gteq' => $date]);

        return $customers;
    }

    public function export($format = null, $destination = null, $fileName = null)
    {
        if ($format === null) {
            $format = $this->scopeConfig->getValue('customerexport/automatic/export_file_type');
        }

        if ($destination === null) {
            $destination = 'file';
        }

        $customers = $this->prepareCustomersCollection();

        $parser = $this->parserFactory->create($format);
        $writer = $this->writerFactory->create($destination);

        $data = $parser->parse($customers);
        $writer->write($data, $fileName);
    }

    public function executeCron()
    {
        if (!$this->scopeConfig->getValue('customerexport/automatic/active')) {
            return;
        }
        $this->export();
    }
}