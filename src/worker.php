<?php

require __DIR__ . '/../vendor/autoload.php';

use Spider\Worker;

$worker = Worker::createFromConfig(require 'config.php');

while (true)
{
  $worker->processTasks();
  sleep(1);
}

