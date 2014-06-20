<?php

namespace Omnipay\Creditcall\Message;

use SimpleXMLElement;

/**
 * Sage Pay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://live.cardeasexml.com/generic.cex';
    protected $testEndpoint = 'https://test.cardeasexml.com/generic.cex';
    protected $action;

    public function getTerminalId()
    {
        return $this->getParameter('terminalId');
    }

    public function setTerminalId($value)
    {
        return $this->setParameter('terminalId', $value);
    }

    public function getTransactionKey()
    {
        return $this->getParameter('transactionKey');
    }

    public function setTransactionKey($value)
    {
        return $this->setParameter('transactionKey', $value);
    }

    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }
    
    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }
    
    public function getLastName()
    {
        return $this->getParameter('lastName');
    }
    
    public function setLastName($value)
    {
        return $this->setParameter('lastName', $value);
    }
    
    
    public function getBillingAddress1()
    {
        return $this->getParameter('billingAddress1');
    }
    
    public function setBillingAddress1($value)
    {
        return $this->setParameter('billingAddress1', $value);
    }
    
    public function getBillingAddress2()
    {
        return $this->getParameter('billingAddress2');
    }
    
    public function setBillingAddress2($value)
    {
        return $this->setParameter('billingAddress2', $value);
    }
    
    public function getBillingCity()
    {
        return $this->getParameter('billingCity');
    }
    
    public function setBillingCity($value)
    {
        return $this->setParameter('billingCity', $value);
    }
    
    public function getBillingPostcode()
    {
        return $this->getParameter('billingPostcode');
    }
    
    public function setBillingPostcode($value)
    {
        return $this->setParameter('billingPostcode', $value);
    }
    
    public function getBillingState()
    {
        return $this->getParameter('billingState');
    }
    
    public function setBillingState($value)
    {
        return $this->setParameter('billingState', $value);
    }
    
    public function getBillingPhone()
    {
        return $this->getParameter('billingPhone');
    }
    
    public function setBillingPhone($value)
    {
        return $this->setParameter('billingPhone', $value);
    }
    
    public function getBillingCountry()
    {
        return $this->getParameter('billingCountry');
    }
    
    public function setBillingCountry($value)
    {
        return $this->setParameter('billingCountry', $value);
    }
    
    public function getShippingAddress1()
    {
        return $this->getParameter('shippingAddress1');
    }
    
    public function setShippingAddress1($value)
    {
        return $this->setParameter('shippingAddress1', $value);
    }
    
    public function getShippingAddress2()
    {
        return $this->getParameter('shippingAddress2');
    }
    
    public function setShippingAddress2($value)
    {
        return $this->setParameter('shippingAddress2', $value);
    }
    
    public function getShippingCity()
    {
        return $this->getParameter('shippingCity');
    }
    
    public function setShippingCity($value)
    {
        return $this->setParameter('shippingCity', $value);
    }
    
    public function getShippingPostcode()
    {
        return $this->getParameter('shippingPostcode');
    }
    
    public function setShippingPostcode($value)
    {
        return $this->setParameter('shippingPostcode', $value);
    }
    
    public function getShippingState()
    {
        return $this->getParameter('shippingState');
    }
    
    public function setShippingState($value)
    {
        return $this->setParameter('shippingState', $value);
    }
    
    public function getShippingPhone()
    {
        return $this->getParameter('shippingPhone');
    }
    
    public function setShippingPhone($value)
    {
        return $this->setParameter('shippingPhone', $value);
    }
    
    public function getShippingCountry()
    {
        return $this->getParameter('shippingCountry');
    }
    
    public function setShippingCountry($value)
    {
        return $this->setParameter('shippingCountry', $value);
    }
     
    public function getCompany()
    {
        return $this->getParameter('company');
    }
    
    public function setCompany($value)
    {
        return $this->setParameter('company', $value);
    }
    
    public function getEmail()
    {
        return $this->getParameter('email');
    }
    
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getVerifyCvv()
    {
        return $this->getParameter('verifyCvv');
    }

    public function setVerifyCvv($value)
    {
        return $this->setParameter('verifyCvv', $value);
    }

    public function getVerifyAddress()
    {
        return $this->getParameter('verifyAddress');
    }

    public function setVerifyAddress($value)
    {
        return $this->setParameter('verifyAddress', $value);
    }

    public function getVerifyZip()
    {
        return $this->getParameter('verifyZip');
    }

    public function setVerifyZip($value)
    {
        return $this->setParameter('verifyZip', $value);
    }

    protected function getBaseData()
    {
        $data = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><Request/>');
        $data->addAttribute('type', 'CardEaseXML');
        $data->addAttribute('version', '1.1.0');

        $transactionDetails = $data->addChild('TransactionDetails');
        $transactionDetails->addChild('MessageType', $this->action);

        $terminalDetails = $data->addChild('TerminalDetails');
        $terminalDetails->addChild('TerminalID', $this->getTerminalId());
        $terminalDetails->addChild('TransactionKey', $this->getTransactionKey());

        $software = $terminalDetails->addChild('Software', 'Omnipay/Creditcall');
        $software->addAttribute('version', '0.1');

        return $data;
    }

    public function sendData($data)
    {
        $headers = array(
            'Content-Type' => 'text/xml',
        );
        $httpResponse = $this->httpClient->post($this->getEndpoint(), $headers, $data->asXML())->send();

        return $this->createResponse($httpResponse->xml());
    }

    public function getEndpoint()
    {
        if ($this->getTestMode()) {
            return $this->testEndpoint;
        }

        return $this->liveEndpoint;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
