<?php

namespace Spider;

use Spider\Protocol\Client;
use Spider\Protocol\Request;

class Worker
{
    private $config;
    private $client;

    /**
     * @param array $config manager's config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client($config['host'], $config['port']);
    }

    /**
     * Requests tasks from the manager and processes them.
     */
    public function processTasks()
    {
        $response = $this->client->sendRequest(new Request(PacketType::GET_TASK));

        if ($response->getType() != 'TASK') {
            return;
        }

        $tasks = $response->getData()->process();
        $this->client->sendRequest(new Request(PacketType::PUSH_TASKS, $tasks));
    }
}

