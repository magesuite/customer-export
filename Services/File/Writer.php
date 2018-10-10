<?php

namespace MageSuite\CustomerExport\Services\File;

use \Magento\Customer\Model\ResourceModel\Customer\Collection;

interface Writer
{
    /**
     * @param Collection $customerCollection
     * @param string $format
     * @param string|null $fileName
     */
    public function write($customerCollection, $format, $fileName = null);
}