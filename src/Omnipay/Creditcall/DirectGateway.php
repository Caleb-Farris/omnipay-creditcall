<?php

namespace Omnipay\Creditcall;

use Omnipay\Common\AbstractGateway;
use Omnipay\Creditcall\Message\CaptureRequest;
use Omnipay\Creditcall\Message\DirectAuthorizeRequest;
use Omnipay\Creditcall\Message\DirectPurchaseRequest;
use Omnipay\Creditcall\Message\RefundRequest;

/**
 * Sage Pay Direct Gateway
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
            'vendor' => '',
            'testMode' => false,
            'simulatorMode' => false,
        );
    }

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }

    public function getSimulatorMode()
    {
        return $this->getParameter('simulatorMode');
    }

    public function setSimulatorMode($value)
    {
        return $this->setParameter('simulatorMode', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectCompleteAuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\CaptureRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\RefundRequest', $parameters);
    }
    
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectCreateTokenRequest', $parameters);
    }
    
    public function repeatPayment(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectRepeatPaymentRequest', $parameters);
    }

    
    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\DirectRemoveTokenRequest', $parameters);
    }
}
