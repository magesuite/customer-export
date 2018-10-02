<?php

namespace MageSuite\CustomerExport\Services\File\Adapter;

use MageSuite\CustomerExport\Services\File\Writer;
use FtpClient\FtpClient;

class FTPAdapter extends AbstractAdapter implements Writer
{
    /**
     * @var FtpClient $ftpClient
     */
    private $ftpClient;

    /**
     * @var FileAdapter $fileAdapter
     */
    private $fileAdapter;

    /**
     * FTPAdapter constructor.
     * @param FtpClient $ftpClient
     */
    public function __construct(
        FtpClient $ftpClient,
        FileAdapter $fileAdapter,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($directoryList, $scopeConfig);

        $this->ftpClient = $ftpClient;
        $this->fileAdapter = $fileAdapter;
    }

    /**
     * @inheritdoc
     * @throws \FtpClient\FtpException
     */
    public function write($data, $fileName = null)
    {
        $filePath = $this->fileAdapter->write($data, $fileName);

        if (!$this->config[AbstractAdapter::CONFIG_FTP_UPLOAD]) {
            return;
        }

        $this->ftpClient->connect(
            $this->config[AbstractAdapter::CONFIG_FTP_HOST],
            (bool) $this->config[AbstractAdapter::CONFIG_FTP_SSL]
        );

        $this->ftpClient->login(
            $this->config[AbstractAdapter::CONFIG_FTP_LOGIN],
            $this->config[AbstractAdapter::CONFIG_FTP_PASSWORD]
        );

        if ($this->config[AbstractAdapter::CONFIG_FTP_PASSIVE]) {
            $this->ftpClient->pasv(true);
        }

        $remoteFilePath = $this->config[AbstractAdapter::CONFIG_FTP_PATH] . $this->config[AbstractAdapter::CONFIG_FILENAME];

        $this->ftpClient->put(
            $remoteFilePath,
            $filePath,
            FTP_ASCII
        );

        $this->ftpClient->close();
    }

}