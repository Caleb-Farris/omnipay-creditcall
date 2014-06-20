<?php

namespace Omnipay\Creditcall;

use Omnipay\Common\AbstractGateway;
use Omnipay\Creditcall\Message\CaptureRequest;
use Omnipay\Creditcall\Message\DirectAuthorizeRequest;
use Omnipay\Creditcall\Message\DirectPurchaseRequest;
use Omnipay\Creditcall\Message\RefundRequest;

/**
 * Creditcall Direct Gateway
 */
class DirectGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Sage Pay Direct';
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

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectAuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectCaptureRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectPurchaseRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectVoidRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectRefundRequest', $parameters);
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
