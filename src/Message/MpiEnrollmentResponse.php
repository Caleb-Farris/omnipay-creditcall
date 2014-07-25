<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall 3D Secure Enrollment Response
 */
class MpiEnrollmentResponse extends AbstractMpiResponse
{
    protected $md;

    public function isSuccessful()
    {
        return isset($this->data->Enrollment->CardHolderEnrolled);
    }

    public function getCardHolderEnrolled()
    {
        return isset($this->data->Enrollment->CardHolderEnrolled) ?
            strtoupper($this->data->Enrollment->CardHolderEnrolled) : null;
    }

    public function getPayerAuthenticationRequest()
    {
        return isset($this->data->Enrollment->PayerAuthenticationRequest) ?
            (string)$this->data->Enrollment->PayerAuthenticationRequest : null;
    }

    public function isRedirect()
    {
        if ($this->getCardHolderEnrolled() === 'Y') {
            return true;
        }

        return false;
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return isset($this->data->Enrollment->AccessControlServerURL) ?
                (string)$this->data->Enrollment->AccessControlServerURL : null;
        }

        return null;
    }

    public function getRedirectData()
    {
        if ($this->isRedirect()) {
            return array(
                'MD' => $this->getMd(),
                'PaReq' => $this->getPayerAuthenticationRequest(),
                'TermUrl' => $this->request->getReturnUrl(),
            );
        }

        return null;
    }

    public function getMd()
    {
        if (is_null($this->md)) {
            $this->md = $this->generateRandomString();
        }

        return $this->md;
    }

    protected function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
