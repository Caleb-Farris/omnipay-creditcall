<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall Direct Capture Request
 */
class DirectCaptureRequest extends AbstractRequest
{
    protected $action = 'Conf';

    public function getData()
    {
        $data = $this->getBaseData();

        $transactionDetails = $data->TransactionDetails[0];
        $transactionDetails->addChild('CardEaseReference', $this->getTransactionReference());

        $cardDetails = $data->addChild('CardDetails');
        $this->setThreeDSecureCredentials($cardDetails);

        return $data;
    }

    public function getThreeDSecureCardHolderEnrolled()
    {
        return $this->getParameter('threeDSecureCardHolderEnrolled');
    }

    public function getThreeDSecureCardHolderEnrolledTranslated()
    {
        $mpiParameter = $this->getParameter('threeDSecureCardHolderEnrolled');

        $xmlParameter = null;
        switch($mpiParameter)
        {
            case 'Y':
                $xmlParameter = 'Yes';
                break;
            case 'N':
                $xmlParameter = 'No';
                break;
            case 'U':
                $xmlParameter = 'Unknown';
                break;
            default:
                $xmlParameter = 'None';
        }

        return $xmlParameter;
    }

    public function setThreeDSecureCardHolderEnrolled($value)
    {
        return $this->setParameter('threeDSecureCardHolderEnrolled', $value);
    }

    public function getThreeDSecureTransactionStatus()
    {
        return $this->getParameter('threeDSecureTransactionStatus');
    }

    public function getThreeDSecureTransactionStatusTranslated()
    {
        $mpiParameter = $this->getParameter('threeDSecureTransactionStatus');

        $xmlParameter = null;
        switch($mpiParameter)
        {
            case 'Y':
                $xmlParameter = 'Successful';
                break;
            case 'N':
                $xmlParameter = 'Failed';
                break;
            case 'U':
                $xmlParameter = 'Unknown';
                break;
            case 'A':
                $xmlParameter = 'Attempted';
                break;
            default:
                $xmlParameter = 'None';
        }

        return $xmlParameter;
    }

    public function setThreeDSecureTransactionStatus($value)
    {
        return $this->setParameter('threeDSecureTransactionStatus', $value);
    }

    public function getThreeDSecureEci()
    {
        return $this->getParameter('threeDSecureEci');
    }

    public function setThreeDSecureEci($value)
    {
        return $this->setParameter('threeDSecureEci', $value);
    }

    public function getThreeDSecureIav()
    {
        return $this->getParameter('threeDSecureIav');
    }

    public function setThreeDSecureIav($value)
    {
        return $this->setParameter('threeDSecureIav', $value);
    }

    public function getThreeDSecureIavAlgorithm()
    {
        return $this->getParameter('threeDSecureIavAlgorithm');
    }

    public function setThreeDSecureIavAlgorithm($value)
    {
        return $this->setParameter('threeDSecureIavAlgorithm', $value);
    }

    public function isThreeDSecureRequired()
    {
        return true;
    }

    public function setThreeDSecureCredentials(\SimpleXMLElement &$data)
    {
        $threeDSecure = $data->addChild('ThreeDSecure');

        $threeDSecure->addChild('CardHolderEnrolled', $this->getThreeDSecureCardHolderEnrolledTranslated());
        $threeDSecure->addChild('ECI', $this->getThreeDSecureEci());

        $iav = $threeDSecure->addChild('IAV', $this->getThreeDSecureIav());
        $iav->addAttribute('algorithm', $this->getThreeDSecureIavAlgorithm());
        $iav->addAttribute('format', 'base64');

        $threeDSecure->addChild('TransactionStatus', $this->getThreeDSecureTransactionStatusTranslated());
    }
}
