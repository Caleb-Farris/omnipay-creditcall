<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall Direct Capture Request
 */
class DirectCaptureRequest extends AbstractRequest
{
    protected $action = 'Conf';

    public function getData()
    {
        $data = $this->getBaseData();

        $data->addChild('CardEaseReference', $this->getTransactionId());

        return $data;
    }

}
