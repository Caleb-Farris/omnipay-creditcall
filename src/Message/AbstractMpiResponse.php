<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Creditcall 3D Secure Response
 */
abstract class AbstractMpiResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function getCode()
    {
        return isset($this->data->Error->Code) ? (string)$this->data->Error->Code : null;
    }

    public function getMessage()
    {
        return isset($this->data->Error->Message) ? (string)$this->data->Error->Message : null;
    }

    public function getDetailMessage()
    {
        return isset($this->data->Error->Detail) ? (string)$this->data->Error->Detail : null;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        return null;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return null;
    }
}
