<?php

namespace MageSuite\CustomerExport\Services\Parser;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer\Collection;

class XmlParser implements Parser
{
    private $rootNodes = [
        'Id', 'Firstname', 'Lastname', 'Email', 'CreatedAt', 'UpdatedAt'
    ];

    private $addressNodes = [
        'Firstname', 'Lastname', 'City', 'Company', 'Country', 'Postcode', 'Region', 'StreetFull', 'Telephone', 'VatId'
    ];

    public function parse(Collection $customers)
    {
        $xml = new \DOMDocument();
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = true;

        $customersNode = $xml->createElement('Customers');

        foreach ($customers as $customer) {
            $customersNode->appendChild(
                $this->parseCustomer($customer, $xml)
            );
        }
        $xml->appendChild($customersNode);
        return $xml->saveXML();
    }

    private function parseCustomer(Customer $customer, \DOMDocument $xml)
    {
        $customerNode = $xml->createElement('Customer');

        foreach ($this->rootNodes as $node) {
            $nodeDataGet = 'get' . $node;
            try {
                $node = $xml->createElement($node, $customer->$nodeDataGet());
                $customerNode->appendChild($node);
            } catch (\Exception $exception) {}
        }
        $addressesNode = $xml->createElement('Addresses');

        foreach ($customer->getAddresses() as $address) {
            $addressNode = $xml->createElement('Address');
            foreach ($this->addressNodes as $node) {
                $nodeDataGet = 'get' . $node;
                try {
                    $node = $xml->createElement($node, $address->$nodeDataGet());
                    $addressNode->appendChild($node);
                } catch (\Exception $exception) {}
            }
            $addressesNode->appendChild($addressNode);
        }
        $customerNode->appendChild($addressesNode);

        return $customerNode;
    }

}