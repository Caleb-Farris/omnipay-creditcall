<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testDirectAuthorizeSuccess()
    {
        // same as in DirectGatewayTest
        $httpResponse = $this->getMockHttpResponse('DirectAuthorizeSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->xml());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('6f3b812a-dafa-e311-983c-00505692354f', $response->getTransactionReference());
        $this->assertSame(array(), $response->getMessage());

        $this->assertSame('a4f483ca-55fc-e311-8ca6-001422187e37', $response->getCardReference());
        $this->assertSame('qo3tCvArxWUxsCONcIWGyHUhXKs=', $response->getCardHash());
    }

}
