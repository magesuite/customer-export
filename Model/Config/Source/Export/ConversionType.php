<?php

namespace MageSuite\CustomerExport\Model\Config\Source\Export;

class ConversionType
{
    /**
     * @var \MageSuite\CustomerExport\Services\Converter\ConverterFactory $converterFactory
     */
    private $converterFactory;

    /**
     * @param MageSuite\CustomerExport\Services\Converter\ConverterFactory $parserFactory
     */
    public function __construct(\MageSuite\CustomerExport\Services\Converter\ConverterFactory $converterFactory)
    {
        $this->converterFactory = $converterFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $converters = array_keys($this->converterFactory->getConverters());

        return array_combine($converters, $converters);
    }
}