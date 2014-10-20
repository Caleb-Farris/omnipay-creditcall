<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Tests\TestCase;

class DirectVoidRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new DirectVoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'transactionReference' => '6f3b812a-dafa-e311-983c-00505692354f',
                'transactionId' => '123',
                'voidReason' => 'DummyReason',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('6f3b812a-dafa-e311-983c-00505692354f', (string)$data->TransactionDetails->CardEaseReference);
        $this->assertSame('123', (string)$data->TransactionDetails->Reference);
        $this->assertSame('DummyReason', (string)$data->TransactionDetails->VoidReason);
    }

}
