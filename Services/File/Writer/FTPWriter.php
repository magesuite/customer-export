<?php

namespace MageSuite\CustomerExport\Services\File\Writer;

use MageSuite\CustomerExport\Services\Converter\ConverterFactory;
use MageSuite\CustomerExport\Services\File\Writer;
use FtpClient\FtpClient;

class FTPWriter extends AbstractWriter implements Writer
{
    /**
     * @var FtpClient $ftpClient
     */
    private $ftpClient;

    /**
     * @var FileWriter $fileWriter
     */
    private $fileWriter;

    /**
     * @param ConverterFactory $converterFactory
     * @param FtpClient $ftpClient
     * @param FileWriter $fileWriter
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ConverterFactory $converterFactory,
        FtpClient $ftpClient,
        FileWriter $fileWriter,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($converterFactory, $directoryList, $scopeConfig);

        $this->ftpClient = $ftpClient;
        $this->fileWriter = $fileWriter;
    }

    /**
     * @inheritdoc
     * @throws \FtpClient\FtpException
     */
    public function write($customerCollection, $format, $fileName = null)
    {
        $filePath = $this->fileWriter->prepareWritePath($fileName);

        $this->fileWriter->write($customerCollection, $format, $fileName);

        if (!$this->config[AbstractWriter::CONFIG_FTP_UPLOAD]) {
            return;
        }

        $this->ftpClient->connect(
            $this->config[AbstractWriter::CONFIG_FTP_HOST],
            (bool) $this->config[AbstractWriter::CONFIG_FTP_SSL]
        );

        $this->ftpClient->login(
            $this->config[AbstractWriter::CONFIG_FTP_LOGIN],
            $this->config[AbstractWriter::CONFIG_FTP_PASSWORD]
        );

        if ($this->config[AbstractWriter::CONFIG_FTP_PASSIVE]) {
            $this->ftpClient->pasv(true);
        }

        $remoteFilePath = $this->config[AbstractWriter::CONFIG_FTP_PATH] . $this->config[AbstractWriter::CONFIG_FILENAME];

        $this->ftpClient->put(
            $remoteFilePath,
            $filePath,
            FTP_ASCII
        );

        $this->ftpClient->close();
    }

}