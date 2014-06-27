<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;

/**
 * Creditcall 3D Secure Authentication Request
 */
class ThreeDSecureAuthenticationRequest extends AbstractThreeDSecureRequest
{

    protected function createResponse($data)
    {
        return $this->response = new ThreeDSecureAuthenticationResponse($this, $data);
    }

    public function getPayerAuthenticationResponse()
    {
        return $this->getParameter('payerAuthenticationResponse');
    }

    public function setPayerAuthenticationResponse($value)
    {
        return $this->setParameter('payerAuthenticationResponse', $value);
    }

    public function getData()
    {
        $data = $this->getBaseData();

        /** @var CreditCard $card */
        $card = $this->getCard();

        $enrollment = $data->addChild('Authentication');

        $enrollment->addChild('PayerAuthenticationResponse', $this->getPayerAuthenticationResponse());
        $enrollment->addChild('Password', $this->getPassword());

        return $data;
    }
}
