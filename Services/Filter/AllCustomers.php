<?php

namespace MageSuite\CustomerExport\Services\Filter;

class AllCustomers implements FilterStrategy
{
    /**
     * @inheritdoc
     */
    public function filterCustomerCollection(\Magento\Customer\Model\ResourceModel\Customer\Collection $customersCollection)
    {
        return $customersCollection;
    }
}