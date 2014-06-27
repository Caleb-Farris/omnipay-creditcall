<?php

namespace Omnipay\Creditcall\Message;

use SimpleXMLElement;

/**
 * Creditcall Abstract 3D Secure Request
 */
abstract class AbstractThreeDSecureRequest extends \Omnipay\Common\Message\AbstractRequest
{

    protected $liveEndpoint = 'https://mpi.cardeasexml.com';
    protected $testEndpoint = 'https://testmpi.cardeasexml.com';

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    protected function getBaseData()
    {
        $data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request/>');

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
        return $this->response = new ThreeDSecureResponse($this, $data);
    }
}
