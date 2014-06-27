<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall 3D Secure Enrollment Response
 */
class ThreeDSecureEnrollmentResponse extends AbstractThreeDSecureResponse
{

    public function isSuccessful()
    {
        return isset($this->data->Response->Enrollment->CardHolderEnrolled);
    }

    public function getCardHolderEnrolled()
    {
        return isset($this->data->Response->Enrollment->CardHolderEnrolled) ?
            strtoupper($this->data->Response->Enrollment->CardHolderEnrolled) : null;
    }

    public function getPayerAuthenticationRequest()
    {
        return isset($this->data->Response->Enrollment->PayerAuthenticationRequest) ?
            strtoupper($this->data->Response->Enrollment->PayerAuthenticationRequest) : null;
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
            return isset($this->data->Response->Enrollment->AccessControlServerURL) ?
                $this->data->Response->Enrollment->AccessControlServerURL : null;
        }

        return null;
    }

    public function getRedirectData()
    {
        if ($this->isRedirect()) {
            return array(
                'PayerAuthenticationRequest' => $this->getPayerAuthenticationRequest(),
                'Password' => $this->request->getPassword(),
            );
        }

        return null;
    }
}
