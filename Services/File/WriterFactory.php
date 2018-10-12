<?php

namespace MageSuite\CustomerExport\Services\File;

class WriterFactory
{
    /**
     * @var \MageSuite\CustomerExport\Services\File\Writer[] $writers
     */
    private $writers = [];


    /**
     * @param array $writers
     */
    public function __construct(array $writers = [])
    {
        $this->writers = $writers;
    }

    /**
     * @param string $format
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \MageSuite\CustomerExport\Services\File\Writer
     */
    public function create($format)
    {
        if (!array_key_exists($format, $this->writers)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                new \Magento\Framework\Phrase(
                    sprintf('Cannot found writer for %s format', $format)
                )
            );
        }
        return $this->writers[$format];
    }

}