<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Creditcall Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function isSuccessful()
    {
        return isset($this->data->Result->LocalResult) && ((string)$this->data->Result->LocalResult) == 0;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getTransactionReference()
    {
        return isset($this->data->TransactionDetails->CardEaseReference) ?
            (string)$this->data->TransactionDetails->CardEaseReference : null;
    }

    public function getMessage()
    {
        if (!isset($this->data->Result->Errors)) {
            return array();
        }

        $errors = array();
        foreach ((array)$this->data->Result->Errors as $error) {
            $errors[] = (string)$error;
        }

        return $errors;
    }

    public function getCardReference()
    {
        return isset($this->data->CardDetails->CardReference) ? (string)$this->data->CardDetails->CardReference : null;
    }

    public function getCardHash()
    {
        return isset($this->data->CardDetails->CardHash) ? (string)$this->data->CardDetails->CardHash : null;
    }

    public function getRedirectUrl()
    {
        return null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return array();
    }
}
