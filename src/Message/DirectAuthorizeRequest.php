<?php

namespace Omnipay\Creditcall\Message;

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

        $transactionDetails = $data->TransactionDetails;

        $amount = $transactionDetails->addChild('Amount', $this->getAmount());
        $amount->addAttribute('unit', 'major');
        $transactionDetails->addChild('CurrencyCode', $this->getCurrency());
        
        //If this is a Token payment, add the Token data item, otherwise its a normal card purchase.
        if( $this->getCardReference() )
        {
            throw new \Exception('Unsupported yet');
            
        }
        else
        {
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


        }

        return $data;
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
