<?php

namespace MageSuite\CustomerExport\Services\Filter;

use \Magento\Customer\Model\ResourceModel\Customer\Collection;

interface FilterStrategy
{
    /**
     * @param Collection $customersCollection
     * @return Collection
     */
    public function filterCustomerCollection(Collection $customersCollection);
}