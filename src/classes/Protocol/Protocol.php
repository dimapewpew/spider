<?php

namespace Spider\Protocol;

class Protocol
{
    const MAX_PACKET_SIZE = 10000;

    /**
     * Encodes packet object to the string
     * @param Packet $packet packet object
     * @return string string packet represenation
     */
    public function packPacket($packet)
    {
        return $packet->getType() . ' ' . serialize($packet->getData());
    }

    /**
     * Decodes string to the packet object
     * @param string $str string packet represenation
     * @return Packet packet object
     */
    public function unpackPacket($str)
    {
        list($type, $data) = explode(' ', $str, 2);
        return new Packet($type , unserialize($data));
    }
}

?>
