<?php

namespace Omnipay\Creditcall\Message;

/**
 * Sage Pay Direct Purchase Request
 */
class DirectPurchaseRequest extends DirectAuthorizeRequest
{
    protected $action = 'PAYMENT';
}
