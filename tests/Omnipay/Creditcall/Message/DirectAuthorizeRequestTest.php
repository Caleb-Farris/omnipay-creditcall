<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Tests\TestCase;

class DirectAuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->request = new DirectAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '12.00',
                'transactionId' => '123',
                'card' => $this->getValidCard(),
            )
        );
    }

    public function testGetDataCustomerDetails()
    {
        $card = $this->request->getCard();
        $data = $this->request->getData();

        $this->assertSame($card->getFirstName(), $data['BillingFirstnames']);
        $this->assertSame($card->getLastName(), $data['BillingSurname']);
        $this->assertSame($card->getBillingAddress1(), $data['BillingAddress1']);
        $this->assertSame($card->getBillingAddress2(), $data['BillingAddress2']);
        $this->assertSame($card->getBillingCity(), $data['BillingCity']);
        $this->assertSame($card->getBillingPostcode(), $data['BillingPostCode']);
        $this->assertSame($card->getBillingState(), $data['BillingState']);
        $this->assertSame($card->getBillingCountry(), $data['BillingCountry']);
        $this->assertSame($card->getBillingPhone(), $data['BillingPhone']);

        $this->assertSame($card->getFirstName(), $data['DeliveryFirstnames']);
        $this->assertSame($card->getLastName(), $data['DeliverySurname']);
        $this->assertSame($card->getShippingAddress1(), $data['DeliveryAddress1']);
        $this->assertSame($card->getShippingAddress2(), $data['DeliveryAddress2']);
        $this->assertSame($card->getShippingCity(), $data['DeliveryCity']);
        $this->assertSame($card->getShippingPostcode(), $data['DeliveryPostCode']);
        $this->assertSame($card->getShippingState(), $data['DeliveryState']);
        $this->assertSame($card->getShippingCountry(), $data['DeliveryCountry']);
        $this->assertSame($card->getShippingPhone(), $data['DeliveryPhone']);
    }

}
