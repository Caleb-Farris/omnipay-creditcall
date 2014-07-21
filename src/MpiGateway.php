<?php

namespace Omnipay\Creditcall;

use Omnipay\Common\AbstractGateway;
use Omnipay\Creditcall\Message\MpiAuthenticationRequest;
use Omnipay\Creditcall\Message\MpiEnrollmentRequest;

/**
 * Creditcall CardEaseMPI Gateway
 */
class MpiGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Creditcall CardEaseMPI Gateway';
    }

    public function getDefaultParameters()
    {
        return array(
            'acquirerBin' => '',
            'merchantId' => '',
            'testMode' => false,
        );
    }

    public function getAcquirerBin()
    {
        return $this->getParameter('acquirerBin');
    }

    public function setAcquirerBin($value)
    {
        return $this->setParameter('acquirerBin', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @param array $parameters
     * @return MpiEnrollmentRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\MpiEnrollmentRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return MpiAuthenticationRequest
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Creditcall\Message\MpiAuthenticationRequest', $parameters);
    }
}
