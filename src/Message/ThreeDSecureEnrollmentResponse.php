<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall 3D Secure Enrollment Response
 */
class ThreeDSecureEnrollmentResponse extends AbstractThreeDSecureResponse
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
                'MD' => $this->request->getTransactionId(),
                'PaReq' => $this->getPayerAuthenticationRequest(),
                'TermUrl' => $this->request->getReturnUrl(),
            );
        }

        return null;
    }
}
