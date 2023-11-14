<?php

namespace MageSuite\CustomerExport\Cron;

class Export
{
    /**
     * @var \MageSuite\CustomerExport\Services\Export\ExporterFactory $exporterFactory
     */
    private $exporterFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    private $scopeConfig;

    public function __construct(
        \MageSuite\CustomerExport\Services\Export\ExporterFactory $exporterFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->exporterFactory = $exporterFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        if (!$this->scopeConfig->getValue('customerexport/automatic/active')) {
            return;
        }
        $this->exporterFactory->create()->export();
    }
}
