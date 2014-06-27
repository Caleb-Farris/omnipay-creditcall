<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Creditcall 3D Secure Response
 */
class ThreeDSecureResponse extends AbstractThreeDSecureResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function isSuccessful()
    {
        return isset($this->data->Response->Enrollment->CardHolderEnrolled);
    }

    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return null;
        }
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        if ($this->isRedirect()) {
            return array();
        }
    }
}
