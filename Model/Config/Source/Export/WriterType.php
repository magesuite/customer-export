<?php

namespace MageSuite\CustomerExport\Model\Config\Source\Export;

class WriterType
{
    /**
     * @var \MageSuite\CustomerExport\Services\File\WriterFactory $writerFactory
     */
    private $writerFactory;

    /**
     * @param \MageSuite\CustomerExport\Services\File\WriterFactory $writerFactory
     */
    public function __construct(\MageSuite\CustomerExport\Services\File\WriterFactory $writerFactory)
    {
        $this->writerFactory = $writerFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $writers = array_keys($this->writerFactory->getWriters());

        return array_combine($writers, $writers);
    }
}
