<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall 3D Secure Enrollment Response
 */
class MpiEnrollmentResponse extends AbstractMpiResponse
{
    protected $storeKey;

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
                'MD' => $this->storeKey(),
                'PaReq' => $this->getPayerAuthenticationRequest(),
                'TermUrl' => $this->request->getReturnUrl(),
            );
        }

        return null;
    }

    public function storeData($additionalData = array())
    {
        /** @var TemporaryStorageInterface $temporaryStorageDriver */
        $temporaryStorageDriver = $this->getTemporaryStorageDriver();
        if ($temporaryStorageDriver) {
            $temporaryStorageDriver->put($this->storeKey(), array(
                'cardHolderEnrolled' => $this->getCardHolderEnrolled(),
                'additionalData' => $additionalData,
            ));
        }
    }

    public function storeKey()
    {
        if (is_null($this->storeKey)) {
            $this->storeKey = $this->generateRandomString();
        }

        return $this->storeKey;
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
