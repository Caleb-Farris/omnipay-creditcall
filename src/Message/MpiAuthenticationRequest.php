<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;

/**
 * Creditcall 3D Secure Authentication Request
 */
class MpiAuthenticationRequest extends AbstractMpiRequest
{

    protected function createResponse($data)
    {
        return $this->response = new MpiAuthenticationResponse($this, $data);
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

        $authentication = $data->addChild('Authentication');

        $authentication->addChild('PayerAuthenticationResponse', $this->getPayerAuthenticationResponse());
        $authentication->addChild('Password', $this->getPassword());

        return $data;
    }
}
