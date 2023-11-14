<?php

namespace MageSuite\CustomerExport\Services\Converter;

class XmlConverter implements Converter
{
    private $rootNodes = [
        'Id', 'Firstname', 'Lastname', 'Email', 'CreatedAt', 'UpdatedAt'
    ];

    private $addressNodes = [
        'Firstname', 'Lastname', 'City', 'Company', 'Country', 'Postcode', 'Region', 'StreetFull', 'Telephone', 'VatId'
    ];

    /**
     * @var \XMLWriter|null
     */
    private $xmlWritter;

    /**
     * @inheritdoc
     */
    public function startConversion()
    {
        $this->xmlWritter = new \XMLWriter();
        $this->xmlWritter->openMemory();
        $this->xmlWritter->startDocument('1.0', 'UTF-8');
        $this->xmlWritter->setIndent(true);
        $this->xmlWritter->startElement('Customers');

        return $this->xmlWritter->flush();
    }

    /**
     * @inheritdoc
     */
    public function convertBatch(\Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollection)
    {
        foreach ($customerCollection as $customer) {
            $this->xmlWritter->startElement('Customer');

            foreach ($this->rootNodes as $node) {
                $nodeDataGet = 'get' . $node;
                try {
                    $this->xmlWritter->startElement($node);
                    $this->xmlWritter->text($customer->$nodeDataGet());
                    $this->xmlWritter->endElement();
                } catch (\Exception $exception) {}
            }
            $this->xmlWritter->startElement('Addresses');

            foreach ($customer->getAddresses() as $address) {
                $this->xmlWritter->startElement('Address');

                foreach ($this->addressNodes as $node) {
                    $nodeDataGet = 'get' . $node;
                    try {
                        $this->xmlWritter->startElement($node);
                        $this->xmlWritter->text($address->$nodeDataGet());
                        $this->xmlWritter->endElement();
                    } catch (\Exception $exception) {}
                }
                $this->xmlWritter->endElement();
            }
            $this->xmlWritter->endElement();
            $this->xmlWritter->endElement();
        }

        return $this->xmlWritter->flush();
    }

    /**
     * @inheritdoc
     */
    public function endConversion()
    {
        $this->xmlWritter->endElement();

        return $this->xmlWritter->flush();
    }
}
