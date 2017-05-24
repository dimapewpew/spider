<?php

namespace Spider;

class WorkerLauncher implements LauncherInterface
{
    private $workers_count;
    private $workers_pids = [];

    /**
     * WorkerLauncher constructor.
     * @param $workers_count number of workers to start
     */
    public function __construct($workers_count)
    {
        $this->workers_count = $workers_count;
    }

    /**
     * Starts worker processes
     */
    public function startWorkers()
    {
        for ($i = 0; $i < $this->workers_count; $i++) {
            $this->workers_pids[] = trim(exec('/usr/bin/php worker.php > /dev/null 2>&1 & echo $!'));
        }
    }
}