<?php

namespace Omnipay;

use Omnipay\Creditcall\MpiGateway;

require 'common.php';

/** @var MpiGateway $g */
$g = Omnipay::create('Creditcall_Mpi');
$g->setTestMode(true);
$g->setPassword('P@ssw0rd');

$action = isset($_GET['action']) ? $_GET['action'] : null;

switch ($action) {
    case 'return':

        $payerAuthenticationResponse = isset($_POST['PaRes']) ? $_POST['PaRes'] : '';
        $storeKey = isset($_POST['MD']) ? $_POST['MD'] : '';

        $request = $g->completeAuthorize(array(
            'payerAuthenticationResponse' => $payerAuthenticationResponse,
        ));
        $response = $request->send();

        if ($response->isSuccessful()) {

            echo 'Request successful<br>';
            echo 'TransactionStatus: ' . $response->getTransactionStatus() .'<br>';
            echo 'ECI: ' . $response->getEci() .'<br>';
            echo 'IAV: ' . $response->getIav() .'<br>';
            echo 'IAV Algorithm: ' . $response->getIavAlgorithm() .'<br>';

        } else {
            echo 'Request not successful';
            var_dump($response->getData());
        }

        $response->setTemporaryStorageDriver(new \TemporaryStorage());
        $data = $response->restoreData($storeKey);
        var_dump($data);

        echo '<br><br><a href="' . url('mpi.php', false) . '">Rerun</a>';

        break;

    default:

        $creditCard = new Common\CreditCard(array(
            'number'            => '6759111011100000',
            'cvv'               => '412',
            'expiryMonth'       => '12',
            'expiryYear'        => '20',
        ));

        $data = array(
            'acquirerBin'       => '123456',
            'merchantId'        => '123456789012345',
            'currency'			=> 'GBP',
            'amount'			=> '66.66',
            'card' 				=> $creditCard,
            'returnUrl'			=> url('mpi.php?action=return'),
        );


        $request = $g->authorize($data);
        $response = $request->send();

        if ($response->isSuccessful()) {

            if ($response->isRedirect()) {

                $temporaryStorage = new \TemporaryStorage();
                $response->setTemporaryStorageDriver($temporaryStorage);
                $response->storeData($data);

                $response->getRedirectResponse()->send();
                exit;
            } else {
                echo 'No redirect needed';
                var_dump($response->getData());
                exit;
            }

        } else {
            echo 'Request not successful';
            var_dump($response->getData());
        }
}
