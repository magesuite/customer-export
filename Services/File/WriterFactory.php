<?php

namespace MageSuite\CustomerExport\Services\File;

class WriterFactory
{
    /**
     * @var \MageSuite\CustomerExport\Services\File\Writer[] $writers
     */
    private $writers = [];

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    private $scopeConfig;


    /**
     * @param array $writers
     */
    public function __construct(array $writers = [], \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->writers = $writers;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string|null $destination
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \MageSuite\CustomerExport\Services\File\Writer
     */
    public function create($destination = null)
    {
        if ($destination === null) {
            $destination = $this->scopeConfig->getValue('customerexport/automatic/write_destination');
        }

        if (!array_key_exists($destination, $this->writers)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                new \Magento\Framework\Phrase(
                    sprintf('Cannot found writer for %s', $destination)
                )
            );
        }
        return $this->writers[$destination];
    }

    /**
     * @return \MageSuite\CustomerExport\Services\File\Writer[]
     */
    public function getWriters()
    {
        return $this->writers;
    }
}