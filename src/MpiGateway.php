<?php

namespace Omnipay\Creditcall;

use Omnipay\Creditcall\Message\ServerAuthorizeRequest;
use Omnipay\Creditcall\Message\ServerCompleteAuthorizeRequest;
use Omnipay\Creditcall\Message\ServerPurchaseRequest;

/**
 * Sage Pay Server Gateway
 */
class MpiGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Sage Pay Server';
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\MpiAuthorizeRequest', $parameters);
    }

    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\MpiCompleteAuthorizeRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\MpiPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }
}
