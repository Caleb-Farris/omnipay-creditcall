<?php

namespace Omnipay\Creditcall;

use Omnipay\Common\AbstractGateway;
use Omnipay\Creditcall\Message\DirectAuthorizeRequest;
use Omnipay\Creditcall\Message\DirectCaptureRequest;
use Omnipay\Creditcall\Message\DirectPurchaseRequest;
use Omnipay\Creditcall\Message\DirectRefundRequest;
use Omnipay\Creditcall\Message\DirectResponse;
use Omnipay\Creditcall\Message\DirectVoidRequest;

/**
 * Creditcall CardEaseXML Gateway
 */
class DirectGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Creditcall CardEaseXML Gateway';
    }

    public function getDefaultParameters()
    {
        return array(
            'terminalId' => '',
            'transactionKey' => '',
            'testMode' => false,
            'verifyCvv' => true,
            'verifyAddress' => false,
            'verifyZip' => false,
        );
    }

    /**
     * @param array $parameters
     * @return DirectAuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectAuthorizeRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return DirectCaptureRequest
     */
    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectCaptureRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return DirectPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectPurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return DirectVoidRequest
     */
    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectVoidRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return DirectRefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectRefundRequest', $parameters);
    }


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
}
