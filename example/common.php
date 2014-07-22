<?php

use Omnipay\Creditcall\Message\TemporaryStorageInterface;

require '../vendor/autoload.php';
require 'Crypt.php';
session_start();

function url($route)
{
    $current_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $current_dir = pathinfo($current_url)['dirname'] . '/';
    $query_string = ( $_SERVER['QUERY_STRING'] !== '' ) ? '?' . $_SERVER['QUERY_STRING'] : '';

    return $current_dir . $route . $query_string;
}

class TemporaryStorage implements TemporaryStorageInterface
{
    protected $sessionKey = 'temporaryStorage';

    /** @var \Crypt */
    protected $crypt;

    public function __construct()
    {
        $this->crypt = new Crypt();
    }

    public function put($key, $data)
    {
        $encryptionKey = $this->generateEncryptionKey();
        $encryptedData = $this->encrypt($data, substr($key, 0, 4) . $encryptionKey);
        $row = array(
            'encryptedData' => $encryptedData,
            'encryptionKey' => $encryptionKey,
        );

        $this->setRow($key, $row);
    }

    public function get($key)
    {
        $row = $this->getRow($key);

        if (!isset($row['encryptedData']) || !isset($row['encryptionKey'])) {
            return null;
        }

        return $this->decrypt($row['encryptedData'], substr($key, 0, 4) . $row['encryptionKey']);
    }

    public function forget($key)
    {
        $this->forgetRow($key);
    }

    protected function keyHash($key)
    {
        return sha1($key);
    }

    protected function getRow($key)
    {
        return isset($_SESSION[$this->sessionKey][$this->keyHash($key)]) ?
            $_SESSION[$this->sessionKey][$this->keyHash($key)] : null;
    }

    protected function setRow($key, $row)
    {
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = array();
        }

        $_SESSION[$this->sessionKey][$this->keyHash($key)] = $row;
    }

    protected function forgetRow($key)
    {
        if (isset($_SESSION[$this->sessionKey][$this->keyHash($key)])) {
            $_SESSION[$this->sessionKey][$this->keyHash($key)] = null;
            unset($_SESSION[$this->sessionKey][$this->keyHash($key)]);
        }
    }

    protected function generateEncryptionKey()
    {
        return substr(bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)), 0, 16);
    }

    protected function encrypt($data, $encryptionKey)
    {
        $this->crypt->setKey($encryptionKey);
        $this->crypt->setComplexTypes(true);
        $this->crypt->setData($data);

        return $this->crypt->encrypt();
    }

    protected function decrypt($encryptedData, $encryptionKey)
    {
        $this->crypt->setKey($encryptionKey);
        $this->crypt->setComplexTypes(true);
        $this->crypt->setData($encryptedData);

        return $this->crypt->decrypt();
    }
}
