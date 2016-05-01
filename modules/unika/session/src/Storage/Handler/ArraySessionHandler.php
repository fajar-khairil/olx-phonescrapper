<?php

namespace Unika\Session\Storage\Handler;

use Illuminate\Support\Arr;

class ArraySessionHandler implements \SessionHandlerInterface
{
	protected $store = [];

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->store = [];
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return Arr::get($this->store,$sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        return Arr::set($this->store,$sessionId,$data);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        unset($this->store[$sessionId]);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        // not required here because memcached will auto expire the records anyhow.
        return true;
    }	
}