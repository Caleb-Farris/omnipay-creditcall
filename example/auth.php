<?php

namespace Omnipay;

require 'common.php';

$g = Omnipay::create('Creditcall_Direct');
$g->setTerminalId('99960713');
$g->setTransactionKey('5CbEvg8hXCe3ASs6');
$g->setPassword('P@ssw0rd');
$g->setTestMode(true);
$g->setThreeDSecureRequired(true);

$creditCard = new \Omnipay\Common\CreditCard(array(
//    'number'            => '5123450000000008',
//    'cvv'               => '513',
//    'number'            => '4123450131003312',
//    'cvv'               => '412',
    'number'            => '4111111111111111',
    'cvv'               => '412',
//    'number'            => '4012000033330026',
//    'cvv'               => '412',
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

$request = $g->threeDSecureEnrollment($data);
$response = $request->send();

if ($response->isSuccessful()) {

    if ($response->isRedirect()) {
        $_SESSION['payment'] = serialize(array(
            'data' => $data,
            '3d' => true,
            'cardHolderEnrolled' => $response->getCardHolderEnrolled(),
        ));

        $response->getRedirectResponse()->send();
        exit;
    } else {
        $_SESSION['payment'] = serialize(array(
            'data' => $data,
            '3d' => false,
            'cardHolderEnrolled' => $response->getCardHolderEnrolled(),
        ));

        header('Location: ' . url('conf.php'));
        exit;
    }

} else {
    var_dump($response->getData());
}
