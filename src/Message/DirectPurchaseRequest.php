<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall Direct Purchase Request
 */
class DirectPurchaseRequest extends DirectAuthorizeRequest {

    public function getData(){
        $data = parent::getData();

        $transactionDetails = $data->TransactionDetails[0];
        $messageType = $transactionDetails->MessageType[0];
        $messageType->addAttribute('autoconfirm', 'true');

        return $data;
    }

}
