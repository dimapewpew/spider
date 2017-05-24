<?php

namespace Spider\Protocol;

interface ServerInterface
{
    /**
     * Processes incoming connections
     */
    public function acceptConnections();

    /**
     * Processes the requests from established connections
     */
    public function processRequests();

    /**
     * Sets the handler for connections and requests
     * @param ServerHandlerInterface $handler
     */
    public function setHandler(ServerHandlerInterface $handler);
}