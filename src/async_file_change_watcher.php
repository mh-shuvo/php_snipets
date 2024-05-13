<?php


//dilo surucu

/**
 * Class Watcher
 */
class Watcher
{
    /**
     * @var array
     */
    private static array $hashes = [];

    private static array $processIds = [];

    /**
     * Monitor a single file for changes asynchronously.
     *
     * @param string $path
     * @param Closure $closure
     * @param int $interval
     * @return void
     */
    public static function file(string $path, Closure $closure, int $interval = 1): void
    {
        $processFork = pcntl_fork();
        if (-1 === $processFork) {
            exit();
        }

        if ($processFork) {
            static::$processIds[] = $processFork;
            return;
        }

        // Child process
        static::watcherFile($path, $closure, $interval);
    }

    /**
     * Watcher function for monitoring file changes.
     *
     * @param string $path
     * @param Closure $closure
     * @param int $interval
     * @return void
     */
    private static function watcherFile(string $path, Closure $closure, int $interval): void
    {
        static::$hashes[$path] = md5_file($path);
        while (true) {
            $hash = md5_file($path);
            if (static::$hashes[$path] !== $hash) {
                static::$hashes[$path] = $hash;
                $closure($path, file_get_contents($path));
            }
            sleep($interval);
        }
    }

    /**
     * Monitor multiple files for changes asynchronously.
     *
     * @param array $files
     * @param Closure $closure
     * @return void
     */
    public static function files(array $files, Closure $closure): void
    {
        foreach ($files as $file) {
            static::file($file, $closure);
        }
    }

    /**
     * Set up signal handler for Ctrl+C (SIGINT).
     *
     * @param Closure $closure
     * @return void
     */
    public static function onCtrlC(Closure $closure): void
    {
        pcntl_signal(SIGINT, static function () use ($closure) {
            foreach (static::$processIds as $id) {
                posix_kill($id, SIGTERM);
            }
            $closure(static::$processIds);
            exit();
        });
    }

    /**
     * Set up signal handler for Ctrl+Z (SIGTSTP).
     *
     * @param Closure(array $processes):void $closure
     * @return void
     */
    public static function onCtrlZ(Closure $closure): void
    {
        pcntl_signal(SIGTSTP, static function () use ($closure) {
            foreach (static::$processIds as $id) {
                posix_kill($id, SIGTERM);
            }
            $closure(static::$processIds);
            exit();
        });
    }

    public static function wait():void
    {
        while (true) {
            sleep(1);
        }
    }
}


Watcher::onCtrlC(static function () {
    echo 'Received Ctrl+C. Exiting...' . PHP_EOL;
});


Watcher::onCtrlZ(static function () {
    echo 'Received Ctrl+Z. Exiting...' . PHP_EOL;
});


Watcher::file('ozet.txt', static function (string $path, string $content) {
    echo "The file $path has been changed. New content: $content" . PHP_EOL;
});

Watcher::files(['test.txt', 'abc.txt'], static function (string $path, string $content) {
    echo "The file $path has been changed. New content: $content" . PHP_EOL;
});


Watcher::wait();
