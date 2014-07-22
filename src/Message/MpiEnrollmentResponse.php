<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall 3D Secure Enrollment Response
 */
class MpiEnrollmentResponse extends AbstractMpiResponse
{

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
                'MD' => $this->request->getMd(),
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
            $key = $this->request->getMd();
            $temporaryStorageDriver->put($key, array(
                'cardHolderEnrolled' => $this->getCardHolderEnrolled(),
                'additionalData' => $additionalData,
            ));
        }
    }
}
