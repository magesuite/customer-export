<?php

namespace MageSuite\CustomerExport\Services\Converter;

use Magento\Framework\Exception\LocalizedException;

class ConverterFactory
{
    /**
     * @var Converter[] $converters
     */
    private $converters = [];

    /**
     * @param array $converters
     */
    public function __construct(array $converters = [])
    {
        $this->converters = $converters;
    }

    /**
     * @param string $format
     * @throws LocalizedException
     * @return Converter
     */
    public function create($format)
    {
        if (!array_key_exists($format, $this->converters)) {
            throw new LocalizedException(
                new \Magento\Framework\Phrase(
                    sprintf('Cannot found converter for %s format', $format)
                )
            );
        }
        return $this->converters[$format];
    }

    /**
     * @return Converter[]
     */
    public function getConverters()
    {
        return $this->converters;
    }
}