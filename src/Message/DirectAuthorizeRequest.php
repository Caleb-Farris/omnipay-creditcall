<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;

/**
 * Creditcall Direct Authorize Request
 */
class DirectAuthorizeRequest extends AbstractRequest
{
    protected $action = 'Auth';

    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBaseData();

        $transactionDetails = $data->TransactionDetails[0];

        $transactionDetails->addChild('Reference', $this->getTransactionId());

        $amount = $transactionDetails->addChild('Amount', $this->getAmount());
        $amount->addAttribute('unit', 'major');
        $transactionDetails->addChild('CurrencyCode', $this->getCurrencyNumeric());

        $cardDetails = $data->addChild('CardDetails');

        $manual = $cardDetails->addChild('Manual');
        $manual->addAttribute('type', 'cnp');

        //If this is a Token payment, add the Token data item, otherwise its a normal card purchase.
        if ($this->getCardReference()) {
            $manual->addChild('CardReference', $this->getCardReference());
            $manual->addChild('CardHash', $this->getCardHash());
        } else {
            /** @var CreditCard $card */
            $card = $this->getCard();

            $card->validate();

            $manual->addChild('PAN', $card->getNumber());
            $expiryDate = $manual->addChild('ExpiryDate', $card->getExpiryDate('ym'));
            $expiryDate->addAttribute('format', 'yyMM');

            if ($card->getStartMonth() && $card->getStartYear()) {
                $startDate = $manual->addChild('StartDate', $card->getStartDate('ym'));
                $startDate->addAttribute('format', 'yyMM');
            }

            if ($card->getIssueNumber()) {
                $manual->addChild('IssueNumber', $card->getIssueNumber());
            }

            if ($this->getVerifyCvv() || $this->getVerifyAddress() || $this->getVerifyZip()) {
                $additionalVerification = $cardDetails->addChild('AdditionalVerification');

                if ($this->getVerifyCvv()) {
                    $additionalVerification->addChild('CSC', $card->getCvv());
                }

                if ($this->getVerifyAddress()) {
                    $additionalVerification->addChild('Address', $card->getAddress1());
                }

                if ($this->getVerifyZip()) {
                    $additionalVerification->addChild('Zip', $card->getPostcode());
                }
            }

            if ($this->is3DSecureRequired()) {
                $this->set3DSecureCredentials($cardDetails);
            }

            $this->setBillingCredentials($transactionDetails);
            $this->setShippingCredentials($transactionDetails);
            $this->setCardHolderCredentials($cardDetails);
        }

        return $data;
    }

    protected function setBillingCredentials(\SimpleXMLElement &$data)
    {
        /** @var CreditCard $card */
        $card = $this->getCard();

        $invoice = $data->addChild('Invoice');
        $address = $invoice->addChild('Address');
        $address->addAttribute('format', 'standard');

        $line1 = $address->addChild('Line', $card->getBillingAddress1());
        $line1->addAttribute('id', 1);

        $line2 = $address->addChild('Line', $card->getBillingAddress2());
        $line2->addAttribute('id', 2);

        $address->addChild('City', $card->getBillingCity());
        $address->addChild('State', $card->getBillingState());
        $address->addChild('ZipCode', $card->getBillingPostcode());
        $address->addChild('Country', $card->getBillingCountry());

        $contact = $address->addChild('Contact');
        $name = $contact->addChild('Name');

        $name->addChild('FirstName', $card->getBillingFirstName());
        $name->addChild('LastName', $card->getBillingLastName());

        $phoneNumberList = $address->addChild('PhoneNumberList');
        $phoneNumber1 = $phoneNumberList->addChild('PhoneNumber', $card->getBillingPhone());
        $phoneNumber1->addAttribute('id', 1);
        $phoneNumber1->addAttribute('type', 'unknown');
    }

    protected function setShippingCredentials(\SimpleXMLElement &$data)
    {
        /** @var CreditCard $card */
        $card = $this->getCard();

        $invoice = $data->addChild('Delivery');
        $address = $invoice->addChild('Address');
        $address->addAttribute('format', 'standard');

        $line1 = $address->addChild('Line', $card->getShippingAddress1());
        $line1->addAttribute('id', 1);

        $line2 = $address->addChild('Line', $card->getShippingAddress2());
        $line2->addAttribute('id', 2);

        $address->addChild('City', $card->getShippingCity());
        $address->addChild('State', $card->getShippingState());
        $address->addChild('ZipCode', $card->getShippingPostcode());
        $address->addChild('Country', $card->getShippingCountry());

        $contact = $address->addChild('Contact');
        $name = $contact->addChild('Name');

        $name->addChild('FirstName', $card->getShippingFirstName());
        $name->addChild('LastName', $card->getShippingLastName());

        $phoneNumberList = $address->addChild('PhoneNumberList');
        $phoneNumber1 = $phoneNumberList->addChild('PhoneNumber', $card->getShippingPhone());
        $phoneNumber1->addAttribute('id', 1);
        $phoneNumber1->addAttribute('type', 'unknown');
    }

    protected function setCardHolderCredentials(\SimpleXMLElement &$data)
    {
        /** @var CreditCard $card */
        $card = $this->getCard();

        $address = $data->addChild('Address');
        $address->addAttribute('format', 'standard');

        $line1 = $address->addChild('Line', $card->getAddress1());
        $line1->addAttribute('id', 1);

        $line2 = $address->addChild('Line', $card->getAddress2());
        $line2->addAttribute('id', 2);

        $address->addChild('City', $card->getCity());
        $address->addChild('State', $card->getState());
        $address->addChild('ZipCode', $card->getPostcode());
        $address->addChild('Country', $card->getCountry());

        $contact = $data->addChild('Contact');

        $emailAddressList = $contact->addChild('EmailAddressList');
        $emailAddress1 = $emailAddressList->addChild('EmailAddress', $card->getEmail());
        $emailAddress1->addAttribute('id', 1);
        $emailAddress1->addAttribute('type', 'other');

        $name = $contact->addChild('Name');
        $name->addChild('FirstName', $card->getFirstName());
        $name->addChild('LastName', $card->getLastName());

        $phoneNumberList = $contact->addChild('PhoneNumberList');
        $phoneNumber1 = $phoneNumberList->addChild('PhoneNumber', $card->getPhone());
        $phoneNumber1->addAttribute('id', 1);
        $phoneNumber1->addAttribute('type', 'unknown');
    }

    public function is3DSecureRequired()
    {
        return true;
    }

    public function set3DSecureCredentials(\SimpleXMLElement &$data)
    {
        $threeDSecure = $data->addChild('ThreeDSecure');

        $threeDSecure->addChild('CardHolderEnrolled', 'Yes');
        $threeDSecure->addChild('ECI', '05');

        $iav = $threeDSecure->addChild('IAV', 'AAABAGVlWIMgAAAAKGVYAAAAAAA=');
        $iav->addAttribute('algorithm', '2');
        $iav->addAttribute('format', 'base64');

        $threeDSecure->addChild('TransactionStatus', 'Successful');
    }

    public function getCardReference()
    {
        return $this->getParameter('cardReference');
    }

    public function setCardReference($value)
    {
        return $this->setParameter('cardReference', $value);
    }

    public function getCardHash()
    {
        return $this->getParameter('cardHash');
    }

    public function setCardHash($value)
    {
        return $this->setParameter('cardHash', $value);
    }
}
