<?php

namespace MageSuite\CustomerExport\Model\Config\Source\Export;

use MageSuite\CustomerExport\Services\Parser\ParserFactory;

class Type
{
    /**
     * @var ParserFactory $parserFactory
     */
    private $parserFactory;

    /**
     * Type constructor.
     * @param ParserFactory $parserFactory
     */
    public function __construct(ParserFactory $parserFactory)
    {
        $this->parserFactory = $parserFactory;
    }

    /**
     * Get file type
     *
     * @return array
     */
    public function toOptionArray()
    {
        $parsers = array_keys($this->parserFactory->getParsers());

        return array_combine($parsers, $parsers);
    }
}