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
            'testMode' => false,
        );
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

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
}
