<?php

namespace MageSuite\CustomerExport\Services\Filter;

class AllCustomers implements \MageSuite\CustomerExport\Services\Filter\FilterStrategy
{
    /**
     * @inheritdoc
     */
    public function filterCustomerCollection(\Magento\Customer\Model\ResourceModel\Customer\Collection $customersCollection)
    {
        return $customersCollection;
    }
}
