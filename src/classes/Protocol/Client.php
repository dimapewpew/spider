<?php

namespace Spider\Protocol;

use Exception;

class Client extends Protocol
{
    private $conn;

    /**
     * Client constructor.
     * @param string $host server's hostname or ip
     * @param int $port server's port
     * @throws Exception
     */
    function __construct($host, $port)
    {
        $this->conn = stream_socket_client('tcp://'.$host.':'.$port, $errno, $errstr);
        if (!$this->conn) {
            throw new Exception($errstr, $errno);
        }
        stream_set_timeout($this->conn, 5);
    }

    function __destruct()
    {
        fclose($this->conn);
    }

    /**
     * Sends request to the server
     * @param Packet $request request packet
     * @return Packet response packet
     * @throws Exception
     */
    function sendRequest($request)
    {
        $meta = stream_get_meta_data($this->conn);
        if ($meta['timed_out'] || feof($this->conn)) {
            throw new Exception('Connection problem');
        }

        $request = $this->packPacket($request);
        fwrite($this->conn, $request);
        $response = fread($this->conn, $this::MAX_PACKET_SIZE);
        $response = $this->unpackPacket($response);
        return $response;
    }
}

