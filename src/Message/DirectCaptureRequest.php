<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall Direct Capture Request
 */
class DirectCaptureRequest extends AbstractDirectRequest
{
    protected $action = 'Conf';

    public function getData()
    {
        $data = $this->getBaseData();

        $transactionDetails = $data->TransactionDetails[0];
        $transactionDetails->addChild('CardEaseReference', $this->getTransactionReference());

        return $data;
    }
}
