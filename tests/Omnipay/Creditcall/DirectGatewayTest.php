<?php

namespace Omnipay\Creditcall;

use Omnipay\Tests\GatewayTestCase;

class DirectGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new DirectGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setTerminalId('99960713');
        $this->gateway->setTransactionKey('5CbEvg8hXCe3ASs6');

        $this->purchaseOptions = array(
            'amount' => '10.00',
            'transactionId' => '123',
            'card' => $this->getValidCard(),
        );

        $this->captureOptions = array(
            'amount' => '10.00',
            'transactionReference' => '6f3b812a-dafa-e311-983c-00505692354f',
        );
    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('DirectAuthorizeSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('6f3b812a-dafa-e311-983c-00505692354f', $response->getTransactionReference());
        $this->assertSame(array(), $response->getMessage());

        $this->assertSame('a4f483ca-55fc-e311-8ca6-001422187e37', $response->getCardReference());
        $this->assertSame('qo3tCvArxWUxsCONcIWGyHUhXKs=', $response->getCardHash());
    }

    public function testAuthorizeFailure()
    {
        $this->setMockHttpResponse('DirectAuthorizeFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(array(), $response->getMessage());
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('DirectAuthorizeSuccess.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('6f3b812a-dafa-e311-983c-00505692354f', $response->getTransactionReference());
        $this->assertSame(array(), $response->getMessage());

        $this->assertSame('a4f483ca-55fc-e311-8ca6-001422187e37', $response->getCardReference());
        $this->assertSame('qo3tCvArxWUxsCONcIWGyHUhXKs=', $response->getCardHash());
    }

    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('DirectAuthorizeFailure.txt');

        $response = $this->gateway->authorize($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(array(), $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('DirectCaptureSuccess.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertSame(array(), $response->getMessage());
    }

    public function testCaptureFailure()
    {
        $this->setMockHttpResponse('DirectCaptureFailure.txt');

        $response = $this->gateway->capture($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(array('CardEaseReferenceInvalid'), $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('DirectRefundSuccess.txt');

        $response = $this->gateway->refund($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertSame(array(), $response->getMessage());
    }

    public function testRefundFailure()
    {
        $this->setMockHttpResponse('DirectRefundFailure.txt');

        $response = $this->gateway->refund($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(array('TransactionAlreadyVoided'), $response->getMessage());
    }

    public function testVoidSuccess()
    {
        $this->setMockHttpResponse('DirectVoidSuccess.txt');

        $response = $this->gateway->void($this->captureOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotNull($response->getTransactionReference());
        $this->assertSame(array(), $response->getMessage());
    }

    public function testVoidFailure()
    {
        $this->setMockHttpResponse('DirectVoidFailure.txt');

        $response = $this->gateway->void($this->captureOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(array('TransactionAlreadyVoided'), $response->getMessage());
    }

}
