<?php

namespace Spider;

interface LauncherInterface
{
    /**
     * Starts workers processes
     */
    public function startWorkers();
}