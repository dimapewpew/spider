<?php

require __DIR__ . '/../vendor/autoload.php';

use Spider\Worker;

$worker = new Worker(require 'config.php');

while (true)
{
  $worker->processTasks();
  sleep(1);
}

