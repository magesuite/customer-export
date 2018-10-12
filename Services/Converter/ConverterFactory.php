<?php

namespace MageSuite\CustomerExport\Services\Converter;

class ConverterFactory
{
    /**
     * @var \MageSuite\CustomerExport\Services\Converter\Converter[] $converters
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
     * @throws \Magento\Framework\Exception\LocalizedException\LocalizedException
     * @return \MageSuite\CustomerExport\Services\Converter\Converter
     */
    public function create($format)
    {
        if (!array_key_exists($format, $this->converters)) {
            throw new \Magento\Framework\Exception\LocalizedException\LocalizedException(
                new \Magento\Framework\Phrase(
                    sprintf('Cannot found converter for %s format', $format)
                )
            );
        }
        return $this->converters[$format];
    }

    /**
     * @return \MageSuite\CustomerExport\Services\Converter\Converter[]
     */
    public function getConverters()
    {
        return $this->converters;
    }
}