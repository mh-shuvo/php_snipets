<?php

use Predis\Client;

//dilo surucu


class RedisSessionHandler implements SessionHandlerInterface
{
    private Client $redis;

    private int $ttl = 3600;

    public function __construct()
    {
        $this->redis = new Client();
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string
    {
        return $this->redis->get(
            $this->generateName($id)
        ) ?: '';
    }

    public function write(string $id, string $data): bool
    {
        $this->redis->setex($this->generateName($id), $this->ttl, $data);
        return true;
    }

    public function destroy(string $id): bool
    {
        return (bool)$this->redis->del(
            $this->generateName($id)
        );
    }


    /**
     * @param string $sessionId
     * @return string
     */
    private function generateName(string $sessionId): string
    {
        return "session:$sessionId";
    }

    #[\Override]
    public function gc(int $max_lifetime): bool
    {
        return true;
    }
}

session_set_save_handler(new RedisSessionHandler(), true);


session_start();

$_SESSION['name'] ??= 'test';
$_SESSION['foo'] ??= 'bar';

print_r($_SESSION);//check it via redis-cli with the ( keys * )command
