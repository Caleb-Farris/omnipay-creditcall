<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\Message\AbstractRequest;
use SimpleXMLElement;

/**
 * Creditcall Abstract Request
 *
 * @method DirectResponse send()
 */
abstract class AbstractDirectRequest extends AbstractRequest
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

    public function getThreeDSecureRequired()
    {
        return $this->getParameter('threeDSecureRequired') == true;
    }

    public function setThreeDSecureRequired($value)
    {
        return $this->setParameter('threeDSecureRequired', $value);
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
        $httpResponse = $this->httpClient->request('POST', $this->getEndpoint(), $headers, $data->asXML());
        $xml =  simplexml_load_string($httpResponse->getBody()->getContents());

        return $this->createResponse($xml);
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
        return $this->response = new DirectResponse($this, $data);
    }
}
