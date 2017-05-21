<?php

require __DIR__ . '/../vendor/autoload.php';

use Spider\Cli;
use Spider\Commander;
use Spider\Task;

$opts = getopt('', ['add', 'status', 'clear', 'path:', 'pattern:']);
$cli = new Cli();
$commander = new Commander(require 'config.php');

/**
 * Add task
 */
if (isset($opts['add'])) {
    if (!is_dir($opts['path'])) {
        $cli->error('Invalid or inaccessible path');
        exit(1);
    }

    if (@preg_match($opts['pattern'],'') === false) {
        $cli->error('Invalid pattern');
        exit(1);
    }

    $task = new Task([
        'path' => $opts['path'],
        'pattern' => $opts['pattern']
    ]);
    $result = $commander->addTask($task);
    $result ? $cli->success('Done') : $cli->error('Error');
}

/**
 * Get status
 */
if (isset($opts['status'])) {
    $status = $commander->getStatus();
    if (!$status) {
        $cli->error('Failed to get status');
    }
    $cli->line('Workers: ' . $status['workers_count'].' Queue: '.$status['queue_size']);
}

/**
 * Clear queue
 */
if (isset($opts['clear'])) {
    $result = $commander->clearQueue();
    $result ? $cli->success('Done') : $cli->error('Error');
}

