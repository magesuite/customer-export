<?php

namespace MageSuite\CustomerExport\Services\File\Writer;

class FTPWriter extends \MageSuite\CustomerExport\Services\File\Writer\AbstractWriter implements \MageSuite\CustomerExport\Services\File\Writer
{
    const CONFIG_FTP_UPLOAD = 'ftp_upload';

    const CONFIG_FTP_PASSIVE = 'ftp_passive';

    const CONFIG_FTP_SSL = 'ftp_ssl';

    const CONFIG_FTP_HOST = 'ftp_host';

    const CONFIG_FTP_LOGIN = 'ftp_login';

    const CONFIG_FTP_PASSWORD = 'ftp_password';

    const CONFIG_FTP_PATH = 'ftp_path';

    /**
     * @var \FtpClient\FtpClient $ftpClient
     */
    private $ftpClient;

    /**
     * @var \MageSuite\CustomerExport\Services\File\Writer\FileWriter $fileWriter
     */
    private $fileWriter;

    /**
     * @param \MageSuite\CustomerExport\Services\Converter\ConverterFactory $converterFactory
     * @param \FtpClient\FtpClient $ftpClient
     * @param \MageSuite\CustomerExport\Services\File\Writer\FileWriter $fileWriter
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \MageSuite\CustomerExport\Services\Converter\ConverterFactory $converterFactory,
        \FtpClient\FtpClient $ftpClient,
        \MageSuite\CustomerExport\Services\File\Writer\FileWriter $fileWriter,
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
    public function write(\Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollection, $format, $fileName = null)
    {
        $temporaryFilePath = $this->fileWriter->prepareWritePath($fileName);

        $this->fileWriter->write($customerCollection, $format, $fileName);

        if (!$this->config[self::CONFIG_FTP_UPLOAD]) {
            return;
        }

        $this->ftpClient->connect(
            $this->config[self::CONFIG_FTP_HOST],
            (bool) $this->config[self::CONFIG_FTP_SSL]
        );

        $this->ftpClient->login(
            $this->config[self::CONFIG_FTP_LOGIN],
            $this->config[self::CONFIG_FTP_PASSWORD]
        );

        if ($this->config[self::CONFIG_FTP_PASSIVE]) {
            $this->ftpClient->pasv(true);
        }

        $remoteFilePath = $this->config[self::CONFIG_FTP_PATH] . $this->config[self::CONFIG_FILENAME];

        $this->ftpClient->put(
            $remoteFilePath,
            $temporaryFilePath,
            FTP_ASCII
        );

        $this->ftpClient->close();
        unlink($temporaryFilePath);
    }

}
