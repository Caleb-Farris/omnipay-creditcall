<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Tests\TestCase;

class DirectRefundRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new DirectRefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'transactionReference' => '6f3b812a-dafa-e311-983c-00505692354f',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('6f3b812a-dafa-e311-983c-00505692354f', (string)$data->TransactionDetails->CardEaseReference);
        $this->assertSame('12.00', (string)$data->TransactionDetails->Amount);
        $this->assertSame('major', (string)$data->TransactionDetails->Amount->attributes()->unit);
    }

}
