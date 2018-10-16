<?php

namespace MageSuite\CustomerExport\Services\Export;

class Exporter
{
    /**
     * @var \MageSuite\CustomerExport\Services\File\WriterFactory $writerFactory
     */
    protected $writerFactory;

    /**
     * @var \MageSuite\CustomerExport\Services\Filter\FilterStrategy $filterStrategy
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
     * @param \MageSuite\CustomerExport\Services\File\WriterFactory $writerFactory
     * @param \MageSuite\CustomerExport\Services\Filter\FilterStrategy $filterStrategy
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \MageSuite\CustomerExport\Services\File\WriterFactory $writerFactory,
        \MageSuite\CustomerExport\Services\Filter\FilterStrategy $filterStrategy,
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
            $ftpActive = $this->scopeConfig->getValue('customerexport/automatic/ftp_upload');
            $destination = $ftpActive === '1' ? 'ftp' : 'file';
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