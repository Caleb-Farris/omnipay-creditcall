<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class DirectAuthorizeTokenRequestTest extends TestCase
{
    /**
     * @var DirectAuthorizeRequest
     */
    public $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new DirectAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'terminalId' => '923632313',
                'transactionKey' => '23ASDas3d323ASs6',
                'amount' => '12.00',
                'currency' => 'GBP',
                'transactionId' => '123',
                'verifyCvv' => true,
                'cardReference' => 'a4f483ca-55fc-e311-8ca6-001422187e37',
                'cardHash' => 'qo3tCvArxWUxsCONcIWGyHUhXKs=',
            )
        );
    }

    public function testTransactionData()
    {
        $data = $this->request->getData();

        $this->assertNull($this->request->getCard());

        $manual = $data->CardDetails->Manual;

        $this->assertSame('cnp', (string)$manual->attributes()->type);
        $this->assertSame('a4f483ca-55fc-e311-8ca6-001422187e37', (string)$manual->CardReference);
        $this->assertSame('qo3tCvArxWUxsCONcIWGyHUhXKs=', (string)$manual->CardHash);

        $this->assertSame('826', (string)$data->TransactionDetails->CurrencyCode);
    }

}
