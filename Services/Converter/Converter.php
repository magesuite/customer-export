<?php

namespace MageSuite\CustomerExport\Services\Converter;

interface Converter
{
    /**
     * @return string
     */
    public function startConversion();

    /**
     * @param Customer $customer
     * @return string
     */
    public function convertBatch(\Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollection);

    /**
     * @return string
     */
    public function endConversion();
}