<?php


/**
 *
 */
class Session
{
    /**
     * @var array
     */
    private array $session;

    /**
     *
     */
    public function __construct()
    {
        $this->start();
        $this->session =& $_SESSION;
    }

    /**
     * @return void
     */
    private function start(): void
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            session_start();
        }
    }

    /**
     * @return void
     */
    private function forceStart(): void
    {
        session_start();
        $this->session =& $_SESSION;
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set(string $key, string $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string
    {
        return $this->session[$key] ?? $default;
    }

    /**
     * @return void
     */
    public function rollback(): void
    {
        session_abort();
        $this->forceStart();
    }

    /**
     * @return void
     */
    public function commit(): void
    {
        session_write_close();
    }
}


$session = new Session();

$session->set('name', 'Dilo surucu');
echo $session->get('name'); //Dilo surucu

$session->rollback();
//$session->commit();


echo $session->get('name'); //null
