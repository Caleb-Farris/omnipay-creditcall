<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;

/**
 * Creditcall 3D Secure Enrollment Request
 */
class MpiEnrollmentRequest extends AbstractMpiRequest
{

    protected function createResponse($data)
    {
        return $this->response = new MpiEnrollmentResponse($this, $data);
    }

    public function getXid()
    {
        return substr(md5(microtime(true) . $this->getPassword() . rand()), 0, 20);
    }

    public function getData()
    {
        $this->validate('password', 'acquirerBin', 'merchantId', 'amount', 'currency');

        $data = $this->getBaseData();

        /** @var CreditCard $card */
        $card = $this->getCard();
        $card->validate();

        $enrollment = $data->addChild('Enrollment');

        $enrollment->addChild('AcquirerBIN', $this->getAcquirerBin());
        $enrollment->addChild('Amount', $this->getAmount());
        $enrollment->addChild('CurrencyCode', $this->getCurrencyNumeric());

        $enrollment->addChild('ExpiryDateMonth', $card->getExpiryDate('m'));
        $enrollment->addChild('ExpiryDateYear', $card->getExpiryDate('Y'));
        $enrollment->addChild('PAN', $card->getNumber());

        $enrollment->addChild('MerchantID', $this->getMerchantId());
        $enrollment->addChild('Password', $this->getPassword());
        $enrollment->addChild('TransactionNarrative', $this->getDescription());
        $enrollment->addChild('XID', $this->getXid());

        return $data;
    }
}
