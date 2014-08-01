<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Creditcall\Constant;

/**
 * Creditcall Response
 * @property \Omnipay\Creditcall\Message\DirectAuthorizeRequest $request
 */
class DirectAuthorizeResponse extends DirectResponse
{

    public function getMessage()
    {
        $errors = parent::getMessage();

        /*
         * In addition to errors directly provided by gateway,
         * we check if we can guess what caused payment to fail.
         * If request was not successful and there is no errors
         * from gateway we check if 3-D secure authorization was
         * not 100% successful or CVV, address or zip verification
         * failed.
         */
        if (! $this->isSuccessful() && count($errors) == 0) {
            if ($this->isCvvNotMatched()) {
                $errors[] = $this->mapError('cvv_not_matched');
            }

            if ($this->isAddressNotMatched()) {
                $errors[] = $this->mapError('address_not_matched');
            }

            if ($this->isZipNotMatched()) {
                $errors[] = $this->mapError('zip_not_matched');
            }

            if (count($errors) == 0 && !$this->isThreeDSecureSuccessful()) {
                $errors[] = $this->mapError('three_d_secure_authorization_failed');
            }
        }

        return $errors;
    }

    public function isCvvNotMatched()
    {
        return isset($this->data->CardDetails->AdditionalVerification->CSC)
            && ((string)$this->data->CardDetails->AdditionalVerification->CSC) == 'notmatched';
    }

    public function isAddressNotMatched()
    {
        return isset($this->data->CardDetails->AdditionalVerification->Address)
        && ((string)$this->data->CardDetails->AdditionalVerification->Address) == 'notmatched';
    }

    public function isZipNotMatched()
    {
        return isset($this->data->CardDetails->AdditionalVerification->Zip)
        && ((string)$this->data->CardDetails->AdditionalVerification->Zip) == 'notmatched';
    }

    public function isThreeDSecureSuccessful()
    {
        if (! $this->request->getThreeDSecureRequired()) {
            return true;
        }

        if (
            $this->request->getThreeDSecureCardHolderEnrolled() !== Constant::CARD_HOLDER_ENROLLED_NO_DIRECT
            || (
                $this->request->getThreeDSecureCardHolderEnrolled() === Constant::CARD_HOLDER_ENROLLED_YES_DIRECT
                && $this->request->getThreeDSecureTransactionStatus() === Constant::TRANSACTION_STATUS_SUCCESSFUL_DIRECT
            )
        ) {
            return true;
        }

        return false;
    }
}
