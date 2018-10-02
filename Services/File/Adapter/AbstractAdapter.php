<?php

namespace MageSuite\CustomerExport\Services\File\Adapter;

abstract class AbstractAdapter
{
    const CONFIG_FTP_UPLOAD = 'ftp_upload';

    const CONFIG_FTP_PASSIVE = 'ftp_passive';

    const CONFIG_FTP_SSL = 'ftp_ssl';

    const CONFIG_FTP_HOST = 'ftp_host';

    const CONFIG_FTP_LOGIN = 'ftp_login';

    const CONFIG_FTP_PASSWORD = 'ftp_password';

    const CONFIG_FTP_PATH = 'ftp_path';

    const CONFIG_FILENAME = 'export_filename';

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
     * XMLWriter constructor.
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->directoryList = $directoryList;
        $this->scopeConfig = $scopeConfig;
        $this->config = $this->scopeConfig->getValue('customerexport/automatic');
    }
}