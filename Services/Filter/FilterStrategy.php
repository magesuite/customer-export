<?php

namespace MageSuite\CustomerExport\Services\Filter;

interface FilterStrategy
{
    /**
     * @param Collection $customersCollection
     * @return Collection
     */
    public function filterCustomerCollection(\Magento\Customer\Model\ResourceModel\Customer\Collection $customersCollection);
}
