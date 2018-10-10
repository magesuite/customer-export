<?php

namespace MageSuite\CustomerExport\Services\Filter;

use \Magento\Customer\Model\ResourceModel\Customer\Collection;

class AllCustomers implements FilterStrategy
{
    /**
     * @inheritdoc
     */
    public function filterCustomerCollection(Collection $customersCollection)
    {
        return $customersCollection;
    }
}