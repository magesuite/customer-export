<?php

namespace MageSuite\CustomerExport\Services\File\Adapter;

use MageSuite\CustomerExport\Services\File\Writer;

class FileAdapter extends AbstractAdapter implements Writer
{
    /**
     * @inheritdoc
     */
    public function write($data, $filename = null)
    {
        if (!$filename) {
            $filename = $this->config[AbstractAdapter::CONFIG_FILENAME];
        }

        $filePath = $this->directoryList->getPath(
                \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR
            ) . '/importexport/';

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        file_put_contents($filePath . $filename, $data);

        return $filePath . $filename;
    }

}