<?php

namespace MageSuite\CustomerExport\Services\File\Writer;

use MageSuite\CustomerExport\Services\File\Writer;

class FileWriter extends AbstractWriter implements Writer
{
    const CHUNK_SIZE = 1000;

    /**
     * @param string|null $fileName
     * @return string
     */
    public function prepareWritePath($fileName = null)
    {
        if (!$fileName) {
            $fileName = $this->config[AbstractWriter::CONFIG_FILENAME];
        }

        $filePath = sprintf('%s/importexport/', $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR));

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        return $filePath . $fileName;
    }

    /**
     * @inheritdoc
     */
    public function write(\Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollection, $format, $fileName = null)
    {
        $filePath = $this->prepareWritePath($fileName);

        $converter = $this->converterFactory->create($format);

        $customerCollection->setPageSize(self::CHUNK_SIZE);
        $chunks = $customerCollection->getLastPageNumber();

        $data = $converter->startConversion();

        file_put_contents($filePath, $data);

        for ($i = 1; $i <= $chunks; $i++) {
            $customerCollection->setCurPage($i);
            $customerCollection->load();
            $data = $converter->convertBatch($customerCollection);
            file_put_contents($filePath, $data, FILE_APPEND);
            $customerCollection->clear();
        }
        $data = $converter->endConversion();

        file_put_contents($filePath, $data, FILE_APPEND);
    }

}