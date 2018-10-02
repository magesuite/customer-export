<?php

namespace MageSuite\CustomerExport\Services\File;

interface Writer
{
    /**
     * @param array $data
     * @param string $fileName
     */
    public function write($data, $fileName = null);
}