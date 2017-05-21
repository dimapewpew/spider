<?php

namespace Spider\Protocol;

interface ServerHandlerInterface
{
    /**
     * Triggered when new connection was accepted by the server
     * @param int $conn new connection id
     */
    public function acceptConnection($conn);

    /**
     * Triggered when client make a request
     * @param int $conn connection id
     * @param Packet $data request packet
     * @return Packet response response packet
     */
    public function processRequest($conn, $data);

    /**
     * Triggered when connection was closed
     * @param int $conn connection id
     */
    public function closeConnection($conn);
}

