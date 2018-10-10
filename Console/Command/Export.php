<?php

namespace MageSuite\CustomerExport\Console\Command;

use MageSuite\CustomerExport\Services\Export\ExporterFactory;
use Symfony\Component\Console\Input\InputOption;

class Export extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var ExporterFactory $exporterFactory
     */
    private $exporterFactory;

    public function __construct(ExporterFactory $exporterFactory)
    {
        parent::__construct();

        $this->exporterFactory = $exporterFactory;
    }

    protected function configure()
    {
        $this
            ->setName('export:customers')
            ->setDescription('Export new/updated customers data.')
            ->addOption('format', null, InputOption::VALUE_OPTIONAL, 'Output file format')
            ->addOption('destination', null, InputOption::VALUE_OPTIONAL, 'Output destination (file, ftp)')
            ->addOption('file-name', null, InputOption::VALUE_OPTIONAL, 'Output file name');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        $this->exporterFactory->create()->export(
            $input->getOption('format'),
            $input->getOption('destination'),
            $input->getOption('file-name')
        );
    }

}


