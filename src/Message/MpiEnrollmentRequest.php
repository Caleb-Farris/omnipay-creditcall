<?php

namespace Omnipay\Creditcall\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Creditcall\Constant;

/**
 * Creditcall 3D Secure Enrollment Request
 */
class MpiEnrollmentRequest extends AbstractMpiRequest
{

    public $supportedBrands = array( CreditCard::BRAND_VISA, CreditCard::BRAND_MASTERCARD );

    protected function createResponse($data)
    {
        return $this->response = new MpiEnrollmentResponse($this, $data);
    }

    public function getXid()
    {
        return substr(md5(microtime(true) . $this->getPassword() . rand()), 0, 20);
    }

    public function getData()
    {
        $this->validate('password', 'amount', 'currency');

        $data = $this->getBaseData();

        /** @var CreditCard $card */
        $card = $this->getCard();
        $card->validate();

        $enrollment = $data->addChild('Enrollment');

        $enrollment->addChild('AcquirerBIN', $this->getAcquirerBin());
        $enrollment->addChild('Amount', $this->getAmount());
        $enrollment->addChild('CurrencyCode', $this->getCurrencyNumeric());

        $enrollment->addChild('ExpiryDateMonth', $card->getExpiryDate('m'));
        $enrollment->addChild('ExpiryDateYear', $card->getExpiryDate('Y'));
        $enrollment->addChild('PAN', $card->getNumber());

        $enrollment->addChild('MerchantID', $this->getMerchantIdPadded());
        $enrollment->addChild('Password', $this->getPassword());
        $enrollment->addChild('TransactionNarrative', $this->getDescription());
        $enrollment->addChild('XID', $this->getXid());

        return $data;
    }

    public function getSupportedAcquirers()
    {
        return array(
            Constant::ACQUIRER_ALLIED_IRISH_BANK_DOMESTIC,
            Constant::ACQUIRER_ALLIED_IRISH_BANK_INTERNATIONAL,
            Constant::ACQUIRER_BARCLAYCARD,
            Constant::ACQUIRER_ELAVON_FINANCIAL_SERVICES,
            Constant::ACQUIRER_HALIFAX_BANK_OF_SCOTLAND,
            Constant::ACQUIRER_HSBC_BANK,
            Constant::ACQUIRER_LLOYDS_TSB,
            Constant::ACQUIRER_WORLDPAY,
        );
    }

    public function getAcquirerBin()
    {
        if ($this->getTestMode()) {
            return '123456';
        }

        /** @var CreditCard $card */
        $card = $this->getCard();
        $brand = $card->getBrand();

        if (! in_array($brand, $this->supportedBrands)) {
            throw new InvalidCreditCardException('Card brand is not supported');
        }

        $bins = $this->getAcquirerBins($brand);

        return isset($bins[$this->getAcquirer()]) ? $bins[$this->getAcquirer()] : null;
    }

    public function getMerchantIdLength()
    {
        /** @var CreditCard $card */
        $card = $this->getCard();
        $brand = $card->getBrand();

        if (! in_array($brand, $this->supportedBrands)) {
            throw new InvalidCreditCardException('Card brand is not supported');
        }

        $lengths = $this->getMerchantIdLengths($brand);

        return isset($lengths[$this->getAcquirer()]) ? $lengths[$this->getAcquirer()] : null;
    }

    public function getMerchantIdPadded()
    {
        if ($this->getTestMode()) {
            return '123456789012345';
        }

        $merchantId = $this->getMerchantId();
        $merchantIdLength = $this->getMerchantIdLength();

        return str_pad((string)$merchantId, $merchantIdLength, '0', STR_PAD_RIGHT);
    }

    protected function getAcquirerBins($brand)
    {
        switch ($brand) {

            case CreditCard::BRAND_VISA:
                return array(
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_DOMESTIC           => '474198',
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_INTERNATIONAL      => '474199',
                    Constant::ACQUIRER_BARCLAYCARD                          => '492900',
                    Constant::ACQUIRER_ELAVON_FINANCIAL_SERVICES            => '446365',
                    Constant::ACQUIRER_HALIFAX_BANK_OF_SCOTLAND             => '405657',
                    Constant::ACQUIRER_HSBC_BANK                            => '429024',
                    Constant::ACQUIRER_LLOYDS_TSB                           => '408532',
                    Constant::ACQUIRER_WORLDPAY                             => '491677',
                );
                break;

            case CreditCard::BRAND_MASTERCARD:
                return array(
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_DOMESTIC           => '543487',
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_INTERNATIONAL      => '510213',
                    Constant::ACQUIRER_BARCLAYCARD                          => '523065',
                    Constant::ACQUIRER_ELAVON_FINANCIAL_SERVICES            => '518422',
                    Constant::ACQUIRER_HALIFAX_BANK_OF_SCOTLAND             => '520334',
                    Constant::ACQUIRER_HSBC_BANK                            => '518644',
                    Constant::ACQUIRER_LLOYDS_TSB                           => '540436',
                    Constant::ACQUIRER_WORLDPAY                             => '542515',
                );
                break;

            default:
                return array();

        }
    }

    protected function getMerchantIdLengths($brand)
    {
        switch ($brand) {

            case CreditCard::BRAND_VISA:
                return array(
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_DOMESTIC           => 11,
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_INTERNATIONAL      => 11,
                    Constant::ACQUIRER_BARCLAYCARD                          => 15,
                    Constant::ACQUIRER_ELAVON_FINANCIAL_SERVICES            => 15,
                    Constant::ACQUIRER_HALIFAX_BANK_OF_SCOTLAND             => 15,
                    Constant::ACQUIRER_HSBC_BANK                            => 15,
                    Constant::ACQUIRER_LLOYDS_TSB                           => 15,
                    Constant::ACQUIRER_WORLDPAY                             => 15,
                );
                break;

            case CreditCard::BRAND_MASTERCARD:
                return array(
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_DOMESTIC           => 11,
                    Constant::ACQUIRER_ALLIED_IRISH_BANK_INTERNATIONAL      => 11,
                    Constant::ACQUIRER_BARCLAYCARD                          => 7,
                    Constant::ACQUIRER_ELAVON_FINANCIAL_SERVICES            => 15,
                    Constant::ACQUIRER_HALIFAX_BANK_OF_SCOTLAND             => 15,
                    Constant::ACQUIRER_HSBC_BANK                            => 15,
                    Constant::ACQUIRER_LLOYDS_TSB                           => 15,
                    Constant::ACQUIRER_WORLDPAY                             => 15,
                );
                break;

            default:
                return array();

        }
    }
}
