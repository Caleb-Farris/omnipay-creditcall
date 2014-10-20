<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Tests\TestCase;

class DirectCaptureRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new DirectCaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'transactionReference' => '6f3b812a-dafa-e311-983c-00505692354f',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('6f3b812a-dafa-e311-983c-00505692354f', (string)$data->TransactionDetails->CardEaseReference);
    }

}
