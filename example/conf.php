<?php

namespace Omnipay;

use Omnipay\Creditcall\Message\ThreeDSecureAuthenticationResponse;

require 'common.php';

$g = Omnipay::create('Creditcall_Direct');
$g->setTerminalId('99960713');
$g->setTransactionKey('5CbEvg8hXCe3ASs6');
$g->setPassword('P@ssw0rd');
$g->setTestMode(true);

$payment = isset($_SESSION['payment']) ? unserialize($_SESSION['payment']) : null;

if (is_null($payment)) {
    echo 'Payment data in session invalid';
    var_dump($_SESSION);
    exit;
}

if ($payment['3d'] == true) {

    $cardHolderEnrolled = isset($payment['cardHolderEnrolled']) ? $payment['cardHolderEnrolled'] : null;
    $paRes = isset($_POST['PaRes']) ? $_POST['PaRes'] : '';
    $request = $g->threeDSecureAuthentication(array(
        'payerAuthenticationResponse' => $paRes,
    ));

    $response = $request->send();

    if (! $response->isSuccessful()) {
        echo '3-D Secure failed';
        var_dump($response->getData());
        exit;
    }

    $threeDSecureData = array(
        'threeDSecureCardHolderEnrolled' => $cardHolderEnrolled,
        'threeDSecureTransactionStatus' => $response->getTransactionStatus(),
        'threeDSecureEci' => $response->getEci(),
        'threeDSecureIav' => $response->getIav(),
        'threeDSecureIavAlgorithm' => $response->getIavAlgorithm(),
    );

    $data = array_merge($payment['data'], $threeDSecureData);

    $request = $g->purchase($data);
    $response = $request->send();

    var_dump($response->getData(), $request->getData()->asXML());

} else {

    $data = array_merge($payment['data']);
    $request = $g->purchase($data);
    $response = $request->send();
    var_dump($response->getData(), $request->getData()->asXML());

}

echo '<br><br><a href="' . url('auth.php') . '">auth.php</a>';

//$_SESSION['payment'] = null;
//unset($_SESSION['payment']);
