<?php

namespace MageSuite\CustomerExport\Services\Export;

use MageSuite\CustomerExport\Services\File\WriterFactory;
use MageSuite\CustomerExport\Services\Filter\FilterStrategy;

class Exporter
{
    /**
     * @var WriterFactory $writerFactory
     */
    protected $writerFactory;

    /**
     * @var FilterStrategy $filterStrategy
     */
    protected $filterStrategy;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @param WriterFactory $writerFactory
     * @param FilterStrategy $filterStrategy
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        WriterFactory $writerFactory,
        FilterStrategy $filterStrategy,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->writerFactory = $writerFactory;
        $this->filterStrategy = $filterStrategy;
        $this->customerFactory = $customerFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return \Magento\Customer\Model\ResourceModel\Customer\Collection
     */
    public function prepareCustomersCollection()
    {
        $customerCollection = $this->customerFactory->create();

        $this->filterStrategy->filterCustomerCollection($customerCollection);

        return $customerCollection;
    }

    public function export($format = null, $destination = null, $fileName = null)
    {
        if ($format === null) {
            $format = $this->scopeConfig->getValue('customerexport/automatic/export_file_type');
        }

        if ($destination === null) {
            $destination = 'file';
        }

        $writer = $this->writerFactory->create($destination);

        $customerColletion = $this->prepareCustomersCollection();

        $writer->write($customerColletion, $format, $fileName);
    }

    public function executeCron()
    {
        if (!$this->scopeConfig->getValue('customerexport/automatic/active')) {
            return;
        }
        $this->export();
    }
}