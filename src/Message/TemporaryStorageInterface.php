<?php

namespace Omnipay\Creditcall\Message;

interface TemporaryStorageInterface
{
    /**
     * Stores encrypted data in temporary storage.
     *
     * @param $key string
     * @param $data mixed
     * @return void
     */
    public function put($key, $data);

    /**
     * Returns decrypted data from temporary storage.
     *
     * @param $key string
     * @return mixed
     */
    public function get($key);

    /**
     * Deletes data from temporary storage.
     *
     * @param $key string
     * @return void
     */
    public function forget($key);
}
