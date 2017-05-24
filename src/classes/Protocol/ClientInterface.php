<?php

namespace Spider\Protocol;

interface ClientInterface
{
    /**
     * @param Packet $packet request packet
     * @return Packet|null response from the server
     */
    public function sendRequest(Packet $packet);
}