<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;

/**
 * Creditcall 3D Secure Enrollment Request
 */
class ThreeDSecureEnrollmentRequest extends AbstractThreeDSecureRequest
{

    protected function createResponse($data)
    {
        return $this->response = new ThreeDSecureEnrollmentResponse($this, $data);
    }

    public function getAcquirerBin()
    {
        if ($this->getTestMode()) {
            return '123456';
        } else {
            throw new \Exception('AcquirerBin - no idea!');
        }
    }

    public function getMerchantId()
    {
        if ($this->getTestMode()) {
            return '123456789012345';
        } else {
            throw new \Exception('MerchantId - no idea!');
        }
    }

    public function getXid()
    {
        return substr(md5(microtime(true) . $this->getPassword() . rand()), 0, 20);
    }

    public function getData()
    {
        $data = $this->getBaseData();

        /** @var CreditCard $card */
        $card = $this->getCard();

        $enrollment = $data->addChild('Enrollment');

        $enrollment->addChild('AcquirerBIN', $this->getAcquirerBin());
        $enrollment->addChild('Amount', $this->getAmount());
        $enrollment->addChild('CurrencyCode', $this->getCurrencyNumeric());

        $enrollment->addChild('ExpityDateMonth', $card->getExpiryDate('m'));
        $enrollment->addChild('ExpityDateYear', $card->getExpiryDate('Y'));
        $enrollment->addChild('PAN', $card->getNumber());

        $enrollment->addChild('MerchantID', $this->getMerchantId());
        $enrollment->addChild('Password', $this->getPassword());
        $enrollment->addChild('TransactionNarrative', $this->getDescription());
        $enrollment->addChild('XID', $this->getXid());

        return $data;
    }
}
