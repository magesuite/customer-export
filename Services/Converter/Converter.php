<?php

namespace MageSuite\CustomerExport\Services\Converter;

use \Magento\Customer\Model\ResourceModel\Customer\Collection;

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
    public function convert(Collection $customerCollection);

    /**
     * @return string
     */
    public function endConversion();
}