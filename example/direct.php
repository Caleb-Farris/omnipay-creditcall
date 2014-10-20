<?php

namespace Omnipay;

use Omnipay\Creditcall\DirectGateway;
use Omnipay\Creditcall\Message\ThreeDSecureAuthenticationResponse;

require 'common.php';

/** @var DirectGateway $g */
$g = Omnipay::create('Creditcall_Direct');
$g->setTerminalId('99960713');
$g->setTransactionKey('5CbEvg8hXCe3ASs6');
$g->setTestMode(true);

$creditCard = new \Omnipay\Common\CreditCard(array(
    'number'            => '4111111111111111',
    'cvv'               => '412',
    'expiryMonth'       => '12',
    'expiryYear'        => '20',
    'firstName'			=> 'Firstname',
    'lastName'			=> 'Lastname',
    'billingAddress1'	=> '56 Gloucester Road',
    'billingAddress2'	=> 'Street Line 2',
    'shippingAddress1'	=> 'Street Line 1',
    'shippingPhone'		=> '1232192232',
    'email'				=> 'jablonski.kce@gmail.com',
    'phone'				=> '123456778',
    'postcode'			=> 'GL1 2US',
));

$transactionId = 'booking-' . time();

$data = array(
    'transactionId'		=> $transactionId,
    'currency'			=> 'GBP',
    'amount'			=> '66.66',
    'card' 				=> $creditCard,
    'returnUrl'			=> url('conf.php'),
);

//$threeDSecureData = array(
//    'threeDSecureCardHolderEnrolled' => $cardHolderEnrolled,
//    'threeDSecureTransactionStatus' => $response->getTransactionStatus(),
//    'threeDSecureEci' => $response->getEci(),
//    'threeDSecureIav' => $response->getIav(),
//    'threeDSecureIavAlgorithm' => $response->getIavAlgorithm(),
//);

$request = $g->purchase($data);
$response = $request->send();

if ($response->isSuccessful()) {
    echo 'Request successful';
    var_dump($response->getData());
} else {
    echo 'Request not successful';
    var_dump($response->getData());
}
