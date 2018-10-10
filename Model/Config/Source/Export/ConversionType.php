<?php

namespace MageSuite\CustomerExport\Model\Config\Source\Export;

use MageSuite\CustomerExport\Services\Converter\ConverterFactory;

class ConversionType
{
    /**
     * @var ConverterFactory $converterFactory
     */
    private $converterFactory;

    /**
     * @param ConverterFactory $parserFactory
     */
    public function __construct(ConverterFactory $converterFactory)
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