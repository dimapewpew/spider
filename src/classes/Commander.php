<?php

namespace Spider;

use Spider\Protocol\Client;
use Spider\Protocol\Request;

class Commander
{
    private $config;
    private $client;

    /**
     * Creates commander instance
     * @param array $config manager's config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client($config['host'], $config['port']);
    }

    /**
     * Adds new task to manager's queue
     * @param Task $task task object
     * @return bool result of operation
     */
    public function addTask($task)
    {
        $response = $this->client->sendRequest(new Request(PacketType::PUSH_TASKS, [$task]));
        return $response && $response->getType() == PacketType::DONE;
    }

    /**
     * Clears manager's queue
     * @return bool result of operation
     */
    public function clearQueue()
    {
        $response = $this->client->sendRequest(new Request(PacketType::CLEAR_QUEUE));
        return $response && $response->getType() == PacketType::DONE;
    }

    /**
     * Gets manager status
     * @return array|null
     */
    public function getStatus()
    {
        $response = $this->client->sendRequest(new Request(PacketType::GET_STATUS));
        if ($response && $response->getType() == PacketType::STATUS) {
            return $response->getData();
        }
        return null;
    }
}

