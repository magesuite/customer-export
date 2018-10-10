<?php

namespace MageSuite\CustomerExport\Services\File;

use \Magento\Framework\Exception\LocalizedException;

class WriterFactory
{
    /**
     * @var Writer[] $writers
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
     * @throws LocalizedException
     * @return Writer
     */
    public function create($format)
    {
        if (!array_key_exists($format, $this->writers)) {
            throw new LocalizedException(
                new \Magento\Framework\Phrase(
                    sprintf('Cannot found writer for %s format', $format)
                )
            );
        }
        return $this->writers[$format];
    }

}