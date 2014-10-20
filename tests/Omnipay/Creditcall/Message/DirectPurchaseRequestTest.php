<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class DirectPurchaseRequestTest extends TestCase
{
    /**
     * @var DirectAuthorizeRequest
     */
    public $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new DirectPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'terminalId' => '923632313',
                'transactionKey' => '23ASDas3d323ASs6',
                'amount' => '12.00',
                'currency' => 'GBP',
                'transactionId' => '123',
                'card' => $this->getValidCard(),
                'verifyCvv' => true,
            )
        );
    }

    public function testTransactionData()
    {
        $data = $this->request->getData();

        $this->assertSame('true', (string)$data->TransactionDetails->MessageType->attributes()->autoconfirm);
    }

}
