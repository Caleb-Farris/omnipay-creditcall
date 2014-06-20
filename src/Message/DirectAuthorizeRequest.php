<?php

namespace Omnipay\Creditcall\Message;
use Omnipay\Common\CreditCard;

/**
 * Sage Pay Direct Authorize Request
 */
class DirectAuthorizeRequest extends AbstractRequest
{
    protected $action = 'Auth';

    public function getData()
    {
        $this->validate('amount');

        $data = $this->getBaseData();

        $transactionDetails = $data->TransactionDetails[0];

        $amount = $transactionDetails->addChild('Amount', $this->getAmount());
        $amount->addAttribute('unit', 'major');
        $transactionDetails->addChild('CurrencyCode', $this->getCurrency());

        $this->setBillingCredentials($transactionDetails);
        $this->setShippingCredentials($transactionDetails);

        //If this is a Token payment, add the Token data item, otherwise its a normal card purchase.
        if( $this->getCardReference() )
        {
            throw new \Exception('Unsupported yet');
            
        }
        else
        {
            /** @var CreditCard $card */
            $card = $this->getCard();

            $card->validate();

            $cardDetails = $data->addChild('CardDetails');

            $manual = $cardDetails->addChild('Manual');
            $manual->addAttribute('type', 'cnp');

            $manual->addChild('PAN', $card->getNumber());
            $expiryDate = $manual->addChild('ExpiryDate', $card->getExpiryDate('ym'));
            $expiryDate->addAttribute('format', 'yyMM');

            if( $card->getStartMonth() && $card->getStartYear() )
            {
                $startDate = $manual->addChild('StartDate', $card->getStartDate('ym'));
                $startDate->addAttribute('format', 'yyMM');
            }

            if( $card->getIssueNumber() )
            {
                $manual->addChild('IssueNumber', $card->getIssueNumber());
            }

            if( $this->getVerifyCvv() || $this->getVerifyAddress() || $this->getVerifyZip() )
            {
                $additionalVerification = $manual->addChild('AdditionalVerification');

                if( $this->getVerifyCvv() )
                {
                    $additionalVerification->addChild('CSC', $card->getCvv());
                }

                if( $this->getVerifyAddress() )
                {
                    $additionalVerification->addChild('Address', $card->getAddress1());
                }

                if( $this->getVerifyZip() )
                {
                    $additionalVerification->addChild('Zip', $card->getPostcode());
                }
            }

            $this->setCardHolderCredentials($cardDetails);

        }

        return $data;
    }

    protected function setBillingCredentials(\SimpleXMLElement &$data){
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

    protected function setShippingCredentials(\SimpleXMLElement &$data){
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

    protected function setCardHolderCredentials(\SimpleXMLElement &$data){
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

        $contact = $address->addChild('Contact');

        $phoneNumberList = $address->addChild('EmailAddressList');
        $phoneNumber1 = $phoneNumberList->addChild('EmailAddress', $card->getEmail());
        $phoneNumber1->addAttribute('id', 1);
        $phoneNumber1->addAttribute('type', 'unknown');

        $name = $contact->addChild('Name');

        $name->addChild('FirstName', $card->getFirstName());
        $name->addChild('LastName', $card->getLastName());

        $phoneNumberList = $address->addChild('PhoneNumberList');
        $phoneNumber1 = $phoneNumberList->addChild('PhoneNumber', $card->getPhone());
        $phoneNumber1->addAttribute('id', 1);
        $phoneNumber1->addAttribute('type', 'unknown');
    }
    
    /**
     * CVV parameter getter
     *
     * @return string
     */
    public function getCvv()
    {
        return $this->getParameter('cvv');
    }

    /**
     * CVV parameter setter
     * Setter added to allow payments with token and cvv.
     * Without setter CVV parameter is stripped out from request parameters.
     *
     * @param $value string
     * @return $this
     */
    public function setCvv($value)
    {
        return $this->setParameter('cvv', $value);
    }

}
