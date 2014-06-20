<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall Direct Void Request
 */
class DirectVoidRequest extends DirectAuthorizeRequest {

    protected $action = 'Void';

    public function getData(){
        $data = $this->getBaseData();

        $transactionDetails = $data->TransactionDetails[0];
        $transactionDetails->addChild('CardEaseReference', $this->getTransactionReference());
        $transactionDetails->addChild('Reference', $this->getTransactionId());
        $transactionDetails->addChild('VoidReason', $this->getVoidReason());

        return $data;
    }

    public function getVoidReason()
    {
        return $this->getParameter('voidReason');
    }

    public function setVoidReason($value)
    {
        return $this->setParameter('voidReason', $value);
    }

}
