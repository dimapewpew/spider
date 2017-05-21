<?php

namespace Spider;

class ManagerDashboard extends Cli
{
    public $manager;

    /**
     * @param Manager $manager spider manager instance
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Renders manager status on the screen
     */
    public function render()
    {
        $queue_size = $this->manager->getQueueSize();
        $worker_statuses = $this->manager->getWorkerStatuses();

        $this->clear();
        $this->line('Spider Manager Dashboard. Press Ctrl+C to exit.');
        $this->line('Queue size: ' . $queue_size);

        $this->hr();
        foreach ($worker_statuses as $i => $status) {
            $status = $status == WorkerStatus::WORKING ? $this->green($status) : $this->gray($status);
            $this->line('Worker '.sprintf('%04d', $i).': '.$status);
        }
        $this->hr();
    }
}

