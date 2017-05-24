<?php

require __DIR__ . '/../vendor/autoload.php';

use Spider\Manager;
use Spider\ManagerDashboard;

$manager = Manager::createFromConfig(require 'config.php');
$manager->startWorkers();
$dashboard = new ManagerDashboard($manager);

while (true)
{
  $manager->handleWorkers();
  $dashboard->render();
  sleep(1);
}

