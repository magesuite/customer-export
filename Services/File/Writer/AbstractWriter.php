<?php

namespace MageSuite\CustomerExport\Services\File\Writer;

abstract class AbstractWriter
{
    const CONFIG_FILENAME = 'export_filename';

    /**
     * @var \MageSuite\CustomerExport\Services\Converter\ConverterFactory $converterFactory
     */
    protected $converterFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var array $config
     */
    protected $config = [];

    /**
     * @param \MageSuite\CustomerExport\Services\Converter\ConverterFactory $converterFactory
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \MageSuite\CustomerExport\Services\Converter\ConverterFactory $converterFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->converterFactory = $converterFactory;
        $this->directoryList = $directoryList;
        $this->scopeConfig = $scopeConfig;
        $this->config = $this->scopeConfig->getValue('customerexport/automatic');
    }
}
