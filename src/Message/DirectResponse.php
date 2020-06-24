<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Creditcall Response
 */
class DirectResponse extends AbstractResponse implements RedirectResponseInterface
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
            $error = (string)$error;
            if ($error !== '') {
                $errors[] = $this->mapError($error);
            }
        }

        return $errors;
    }

    public function getCode()
    {
        return isset($this->data->Result->LocalResult) ? (string)$this->data->Result->LocalResult : null;
    }

    public function getCardType()
    {
        return isset($this->data->CardDetails->CardScheme) ? (string)$this->data->CardDetails->CardScheme->Description : null;
    }

    public function getCardReference()
    {
        return isset($this->data->CardDetails->CardReference) ? (string)$this->data->CardDetails->CardReference : null;
    }

    public function getCardHash()
    {
        return isset($this->data->CardDetails->CardHash) ? (string)$this->data->CardDetails->CardHash : null;
    }

    public function getAuthCode()
    {
        return isset($this->data->Result->AuthCode) ? (string)$this->data->Result->AuthCode : null;
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

    protected function mapError($error)
    {
        $errorsMap = array(
            'CSC Invalid Length.'                       => 'The CVV provided is invalid.',
            'AmountTooSmall'                            => 'The amount is too small for payment to be processed.',
            'cvv_not_matched'                           => 'The CVV provided is invalid.',
            'address_not_matched'                       => 'The Address provided is invalid.',
            'zip_not_matched'                           => 'The Zip code provided is invalid.',
            'three_d_secure_authorization_failed'       =>
                'Error occurred while processing payment.' .
                ' 3-D Secure authorization was not fully successful.',
        );

        if (array_key_exists($error, $errorsMap)) {
            return $errorsMap[$error];
        }

        return $error;
    }
}
