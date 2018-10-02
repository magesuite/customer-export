<?php

namespace MageSuite\CustomerExport\Services\Parser;

use Magento\Customer\Model\ResourceModel\Customer\Collection;

interface Parser
{
    /**
     * @param Customer $customer
     * @return array
     */
    public function parse(Collection $customers);
}