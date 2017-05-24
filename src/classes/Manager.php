<?php

namespace Spider;

use Spider\Protocol\Packet;
use Spider\Protocol\Response;
use Spider\Protocol\Server;
use Spider\Protocol\ServerInterface;
use Spider\Protocol\ServerHandlerInterface;
use SplQueue;

class Manager implements ServerHandlerInterface
{
    private $queue;
    private $workers;
    private $server;
    private $launcher;

    public static function createFromConfig($config)
    {
        $launcher = new WorkerLauncher($config['workers']);
        $server = new Server($config['host'], $config['port']);
        return new self($server, $launcher);
    }

    /**
     * Manager constructor.
     * @param ServerInterface $server
     * @param LauncherInterface $launcher
     */
    public function __construct($server, $launcher)
    {
        $this->workers = [];
        $this->queue = new SplQueue();
        $this->server = $server;
        $this->server->setHandler($this);
        $this->launcher = $launcher;
    }

    /**
     * Accepts worker connections and processes requests
     */
    public function handleWorkers()
    {
        $this->server->acceptConnections();
        $this->server->processRequests();
    }

    /**
     * Server's hook. Called on new worker connection
     * @param int $conn connection id
     * @see ServerHandlerInterface
     */
    public function acceptConnection($conn)
    {
        $this->workers[$conn] = WorkerStatus::CONNECTED;
    }

    /**
     * Server's hook. Called on new request from the worker
     * @param int $conn connection id
     * @param Packet $request request object
     * @return Packet
     * @see ServerHandlerInterface
     */
    public function processRequest($conn, $request)
    {
        switch ($request->getType())
        {
            case PacketType::STATUS:
                return $this->processGetStatusRequest($conn, $request);
            case PacketType::GET_TASK:
                return $this->processGetTaskRequest($conn, $request);
            case PacketType::PUSH_TASKS:
                return $this->processPushTasksRequest($conn, $request);
            case PacketType::CLEAR_QUEUE:
                return $this->processClearQueueRequest($conn, $request);
        }

        return new Packet('NONE');
    }

    /**
     * @param int $conn connection id
     * @param Packet $request request packet
     * @return Packet response packet
     */
    private function processGetStatusRequest($conn, $request)
    {
        $status = [
            'workers_count' => count($this->workers),
            'queue_size' => $this->queue->count()
        ];
        return new Response(PacketType::STATUS, $status);
    }

    /**
     * Processes the request to dequeue task from the main queue.
     * @param int $conn connection id
     * @param Packet $request the request of type "GET_TASK"
     * @return Packet the response of types: "TASK", "NONE"
     */
    private function processGetTaskRequest($conn, $request)
    {
        if (!$this->queue->count()) {
            return new Response(PacketType::NONE);
        }
        $task = $this->queue->dequeue();
        $this->workers[$conn] = WorkerStatus::WORKING;
        return new Response(PacketType::TASK, $task);
    }

    /**
     * Processes the request to enqueue new tasks.
     * @param int $conn connection id
     * @param Packet $request the request with array of tasks
     * @return Packet the response of type "DONE".
     */
    private function processPushTasksRequest($conn, $request)
    {
        foreach($request->getData() as $task) {
            $this->queue->enqueue($task);
        }
        $this->workers[$conn] = WorkerStatus::IDLE;
        return new Response(PacketType::DONE);
    }

    /**
     * Processes commander's request to clear the queue
     * @param int $conn connection id
     * @param Packet $request request packet
     * @return Packet response packet
     */
    private function processClearQueueRequest($conn, $request)
    {
        while ($this->queue->count() > 0) {
            $this->queue->dequeue();
        }
        return new Response(PacketType::DONE);
    }

    /**
     * Server's hook. Called on worker disconnect
     * @param int $conn connection id
     * @see ServerHandlerInterface
     */
    public function closeConnection($conn)
    {
        unset($this->workers[$conn]);
    }

    /**
     * Starts worker processes. After start each worker connects to a manager.
     */
    public function startWorkers()
    {
        $this->launcher->startWorkers();
    }

    /**
     * Returns number of tasks in the queue
     * @return int number of queued tasks
     */
    public function getQueueSize()
    {
        return $this->queue->count();
    }

    /**
     * Returns array of worker statuses
     * @return array worker statuses
     */
    public function getWorkerStatuses()
    {
        return $this->workers;
    }
}

