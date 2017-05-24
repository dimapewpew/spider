<?php

namespace Spider\Protocol;

use Exception;

class Server extends Protocol implements ServerInterface
{
    private $socket;
    private $connections;
    private $handler;

    /**
     * Server constructor.
     * @param string $host listening address
     * @param int $port listening port
     * @throws Exception
     */
    public function __construct($host, $port)
    {
        $this->socket = stream_socket_server('tcp://'.$host.':'.$port, $errno, $errstr);
        if (!$this->socket) {
            throw new Exception($errstr, $errno);
        }
        $this->handler = new ServerHandler();
    }

    public function __destruct()
    {
        foreach ($this->connections as $conn)
        {
            fclose($conn);
        }
        fclose($this->socket);
    }

    /**
     * Accepts new incoming connection and calls handler's hook.
     */
    public function acceptConnections()
    {
        if (stream_select($read = [$this->socket], $write = NULL, $except = NULL, 0)) {
            $conn = stream_socket_accept($this->socket, -1);
            $this->connections[intval($conn)] = $conn;
            $this->handler->acceptConnection($conn);
        }
    }

    /**
     * Processes the requests from connected clients
     */
    public function processRequests()
    {
        if (!$this->connections) {
            return true;
        }

        if (!@stream_select($read = $this->connections, $write = NULL, $except = NULL, 0)) {
            return true;
        }

        foreach($read as $conn) {
            $request = fread($conn, $this::MAX_PACKET_SIZE);
            if ($request) {
                $request = $this->unpackPacket($request);
                $response = $this->handler->processRequest($conn, $request);
                $response = $this->packPacket($response);
                fwrite($conn, $response);
            } else {
                $this->handler->closeConnection($conn);
                fclose($conn);
            }
        }

        return true;
    }

    /**
     * @param ServerHandlerInterface $handler
     */
    public function setHandler(ServerHandlerInterface $handler)
    {
        $this->handler = $handler;
    }
}

