<?php

namespace Spider\Protocol;

class Packet
{
    private $type;
    private $data;

    /**
     * Creates new packet
     * @param string $type type
     * @param mixed $data payload
     */
    public function __construct($type, $data = '')
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

