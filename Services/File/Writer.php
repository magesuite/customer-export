<?php

namespace MageSuite\CustomerExport\Services\File;

interface Writer
{
    /**
     * @param \Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollection
     * @param string $format
     * @param string|null $fileName
     */
    public function write(\Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollection, $format, $fileName = null);
}
