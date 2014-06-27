<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;

/**
 * Creditcall 3D Secure Enrollment Request
 */
class ThreeDSecureEnrollmentRequest extends AbstractThreeDSecureRequest
{

    public function getData()
    {
        $data = $this->getBaseData();

        /** @var CreditCard $card */
        $card = $this->getCard();

        $enrollment = $data->addChild('Enrollment');

        $enrollment->addChild('AcquirerBIN', $acquirerBin);
        $enrollment->addChild('Amount', $this->getAmount());
        $enrollment->addChild('CurrencyCode', $this->getCurrencyNumeric());

        $enrollment->addChild('ExpityDateMonth', $card->getExpiryDate('m'));
        $enrollment->addChild('ExpityDateYear', $card->getExpiryDate('Y'));
        $enrollment->addChild('PAN', $card->getNumber());

        $enrollment->addChild('MerchantID', $merchantId);
        $enrollment->addChild('Password', $this->getPassword());
        $enrollment->addChild('TransactionNarrative', $this->getDescription());
        $enrollment->addChild('XID', $xid);

        return $data;
    }
}
