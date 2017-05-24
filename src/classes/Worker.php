<?php

namespace Spider;

use Spider\Protocol\Client;
use Spider\Protocol\Request;

class Worker
{
    private $client;

    public static function createFromConfig($config)
    {
        $client = new Client($config['host'], $config['port']);
        return new self($client);
    }

    /**
     * @param Client $client spider protocol client
     */
    public function __construct($client)
    {
        $this->client = $client;
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

