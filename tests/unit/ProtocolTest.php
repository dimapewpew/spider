<?php

use Spider\Protocol\Packet;
use Spider\Protocol\Protocol;

class ProtocolTest extends \Codeception\Test\Unit
{
    public function testPacketTranscoding()
    {
      $packet = new Packet('TEST', ['payload' => true]);
      $protocol = new Protocol();

      $raw = $protocol->packPacket($packet);
      $decoded = $protocol->unpackPacket($raw);

      $this->assertEquals($packet->type, $decoded->type);
      $this->assertEquals($packet->data, $decoded->data);
    }
}

