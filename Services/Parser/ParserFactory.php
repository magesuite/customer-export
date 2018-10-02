<?php

namespace MageSuite\CustomerExport\Services\Parser;

use Magento\Framework\Exception\LocalizedException;
use Zend\Di\Exception\ClassNotFoundException;

class ParserFactory
{
    /**
     * @var array
     */
    private $classMappings = [];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * ParserFactory constructor.
     * @param array $classMappings
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(array $classMappings = [], \Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->classMappings = $classMappings;
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $format
     * @throws LocalizedException
     * @return Parser
     */
    public function create($format)
    {
        if (!array_key_exists($format, $this->classMappings)) {
            throw new LocalizedException(sprintf('Cannot found parser for %s format', $format));
        }
        return $this->classMappings[$format];
    }

    public function getParsers()
    {
        return $this->classMappings;
    }

}