<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall 3D Secure Authentication Response
 */
class ThreeDSecureAuthenticationResponse extends AbstractThreeDSecureResponse
{

    public function isSuccessful()
    {
        return isset($this->data->Response->Enrollment->CardHolderEnrolled);
    }

}
