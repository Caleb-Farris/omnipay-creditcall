<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class DirectAuthorizeRequestTest extends TestCase
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
                'testMode' => true,
                'amount' => '12.00',
                'currency' => 'GBP',
                'transactionId' => '123',
                'card' => $this->getValidCard(),
                'verifyCvv' => true,
            )
        );
    }

    public function testEndpoint()
    {
        $this->assertSame('https://test.cardeasexml.com/generic.cex', $this->request->getEndpoint());
    }

    public function testBaseData()
    {
        $data = $this->request->getData();

        $this->assertSame('Auth', (string)$data->TransactionDetails->MessageType);

        $this->assertSame('923632313', (string)$data->TerminalDetails->TerminalID);
        $this->assertSame('23ASDas3d323ASs6', (string)$data->TerminalDetails->TransactionKey);
    }

    public function testTransactionData()
    {
        /** @var CreditCard $card */
        $card = $this->request->getCard();
        $data = $this->request->getData();

        $this->assertSame('123', (string)$data->TransactionDetails->Reference);
        $this->assertSame('12.00', (string)$data->TransactionDetails->Amount);
        $this->assertSame('major', (string)$data->TransactionDetails->Amount->attributes()->unit);
        $this->assertSame('GBP', (string)$data->TransactionDetails->CurrencyCode);

        $manual = $data->CardDetails->Manual;

        $this->assertSame('cnp', (string)$manual->attributes()->type);
        $this->assertSame($card->getNumber(), (string)$manual->PAN);
        $this->assertSame($card->getExpiryDate('ym'), (string)$manual->ExpiryDate);
        $this->assertSame('', (string)$manual->StartDate);
        $this->assertSame('', (string)$manual->IssueNumber);
        $this->assertSame((string)$card->getCvv(), (string)$data->CardDetails->AdditionalVerification->CSC);
    }

    public function testGetDataCustomerDetails()
    {
        /** @var CreditCard $card */
        $card = $this->request->getCard();
        $data = $this->request->getData();

        $address = $data->CardDetails->Address;

        $this->assertSame($card->getFirstName(), (string)$address->Contact->Name->FirstName);
        $this->assertSame($card->getLastName(), (string)$address->Contact->Name->LastName);
        $this->assertSame($card->getPhone(), (string)$address->PhoneNumberList->PhoneNumber[0]);
        $this->assertSame($card->getAddress1(), (string)$address->Line[0]);
        $this->assertSame($card->getAddress2(), (string)$address->Line[1]);
        $this->assertSame($card->getCity(), (string)$address->City);
        $this->assertSame($card->getState(), (string)$address->State);
        $this->assertSame($card->getPostcode(), (string)$address->ZipCode);
        $this->assertSame($card->getCountry(), (string)$address->Country);
        $this->assertContains($card->getEmail(), array((string)$address->EmailAddressList->EmailAddress[0], null));

        $address = $data->TransactionDetails->Invoice->Address;

        $this->assertSame($card->getBillingFirstName(), (string)$address->Contact->Name->FirstName);
        $this->assertSame($card->getBillingLastName(), (string)$address->Contact->Name->LastName);
        $this->assertSame($card->getBillingPhone(), (string)$address->PhoneNumberList->PhoneNumber[0]);
        $this->assertSame($card->getBillingAddress1(), (string)$address->Line[0]);
        $this->assertSame($card->getBillingAddress2(), (string)$address->Line[1]);
        $this->assertSame($card->getBillingCity(), (string)$address->City);
        $this->assertSame($card->getBillingState(), (string)$address->State);
        $this->assertSame($card->getBillingPostcode(), (string)$address->ZipCode);
        $this->assertSame($card->getBillingCountry(), (string)$address->Country);

        $address = $data->TransactionDetails->Delivery->Address;

        $this->assertSame($card->getShippingFirstName(), (string)$address->Contact->Name->FirstName);
        $this->assertSame($card->getShippingLastName(), (string)$address->Contact->Name->LastName);
        $this->assertSame($card->getShippingPhone(), (string)$address->PhoneNumberList->PhoneNumber[0]);
        $this->assertSame($card->getShippingAddress1(), (string)$address->Line[0]);
        $this->assertSame($card->getShippingAddress2(), (string)$address->Line[1]);
        $this->assertSame($card->getShippingCity(), (string)$address->City);
        $this->assertSame($card->getShippingState(), (string)$address->State);
        $this->assertSame($card->getShippingPostcode(), (string)$address->ZipCode);
        $this->assertSame($card->getShippingCountry(), (string)$address->Country);
    }

}
