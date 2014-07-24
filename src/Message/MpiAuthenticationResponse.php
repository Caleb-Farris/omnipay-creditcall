<?php

namespace Omnipay\Creditcall\Message;

/**
 * Creditcall 3D Secure Authentication Response
 */
class MpiAuthenticationResponse extends AbstractMpiResponse
{

    public function isSuccessful()
    {
        return $this->getTransactionStatus() !== 'N';
    }

    public function getTransactionStatus()
    {
        return isset($this->data->Authentication->TransactionStatus) ?
            strtoupper((string)$this->data->Authentication->TransactionStatus) : null;
    }

    public function getEci()
    {
        return isset($this->data->Authentication->ECI) ? (string)$this->data->Authentication->ECI : null;
    }

    public function getIav()
    {
        return isset($this->data->Authentication->IAV) ? (string)$this->data->Authentication->IAV : null;
    }

    public function getIavAlgorithm()
    {
        return isset($this->data->Authentication->IAVAlgorithm) ?
            (string)$this->data->Authentication->IAVAlgorithm : null;
    }

    public function restoreData($storeKey)
    {
        /** @var TemporaryStorageInterface $temporaryStorageDriver */
        $temporaryStorageDriver = $this->getTemporaryStorageDriver();
        if ($temporaryStorageDriver) {
            $data = $temporaryStorageDriver->get($storeKey);
            $temporaryStorageDriver->forget($storeKey);

            return $data;
        }

        return null;
    }

    public function forgetData($storeKey)
    {
        /** @var TemporaryStorageInterface $temporaryStorageDriver */
        $temporaryStorageDriver = $this->getTemporaryStorageDriver();

        if ($temporaryStorageDriver) {
            $temporaryStorageDriver->forget($storeKey);
        }
    }
}
