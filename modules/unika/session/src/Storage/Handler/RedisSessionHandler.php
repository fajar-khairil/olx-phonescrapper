<?php

namespace Unika\Session\Handler;
use Illuminate\Redis\Database as Redis;

class RedisSessionHandler implements \SessionHandlerInterface
{
	protected $redis;
	protected $minutes;

	public function __construct(Redis $redis,$minutes)
	{
		$this->redis = $redis;
		$this->minutes = $minutes;
	}

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
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        return $this->redis->get($sessionId, '');
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        return $this->redis->put($sessionId, $data, $this->minutes);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        return $this->redis->forget($sessionId);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        return true;
    }
}