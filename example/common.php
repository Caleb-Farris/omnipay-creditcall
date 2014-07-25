<?php

use Omnipay\Creditcall\Message\TemporaryStorageInterface;

require '../vendor/autoload.php';
require 'Crypt.php';
session_start();

function url($route, $include_query = true)
{
    $current_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $current_dir = pathinfo($current_url)['dirname'] . '/';
    $query_string = ( $include_query && $_SERVER['QUERY_STRING'] !== '' ) ? '?' . $_SERVER['QUERY_STRING'] : '';

    return $current_dir . $route . $query_string;
}

class TemporaryStorage
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
        $encryptionKey = $this->setEncryptionKey($key);
        $encryptedData = $this->encrypt($data, $encryptionKey);

        $this->setRow($key, $encryptedData);
    }

    public function get($key)
    {
        $encryptedData = $this->getRow($key);
        $encryptionKey = $this->getEncryptionKey($key);

        if (is_null($encryptedData) || is_null($encryptionKey)) {
            return null;
        }

        return $this->decrypt($encryptedData, $encryptionKey);
    }

    public function forget($key)
    {
        $this->forgetRow($key);
        $this->forgetEncryptionKey($key);
    }

    public function flushStorage()
    {
        if (isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey]= null;
            unset($_SESSION[$this->sessionKey]);
        }
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

    protected function getEncryptionKey($key)
    {
        if (! isset($_COOKIE[$this->keyHash($key)])) {
            return null;
        }

        return $_COOKIE[$this->keyHash($key)];
    }

    protected function setEncryptionKey($key)
    {
        $encryptionKey = $this->generateEncryptionKey();
        if (setcookie($this->keyHash($key), $encryptionKey, 0, '/') === false) {
            throw new \Exception('Cookie cannot be set');
        }

        $_COOKIE[$this->keyHash($key)] = $encryptionKey;

        return $encryptionKey;
    }

    protected function forgetEncryptionKey($key)
    {
        if (isset($_COOKIE[$this->keyHash($key)])) {
            $_COOKIE[$this->keyHash($key)] = null;
            unset($_COOKIE[$this->keyHash($key)]);
            setcookie($this->keyHash($key), '', time()-3600, '/');
        }
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
