<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall Direct Refund Request
 */
class DirectRefundRequest extends DirectAuthorizeRequest {

    protected $action = 'Refund';

    public function getData(){
        $data = $this->getBaseData();

        $transactionDetails = $data->TransactionDetails[0];
        $transactionDetails->addChild('CardEaseReference', $this->getTransactionReference());

        if( ! is_null( $this->getAmount() ) )
        {
            $amount = $transactionDetails->addChild('Amount', $this->getAmount());
            $amount->addAttribute('unit', 'major');
        }

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
