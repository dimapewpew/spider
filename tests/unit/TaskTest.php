<?php

use Codeception\Util\Stub;
use Spider\Task;

class TaskTest extends \Codeception\Test\Unit
{
    public function testTaskProcessing()
    {
      $details = [
        'path' => 'tests/fixtures/crap',
        'pattern' => '/^1\.txt$/'
      ];
      $task = Stub::make(new Task(), [
        'details' => $details,
        'unlink' => Stub::exactly(1)
      ]);

      $result = $task->process();
      $this->assertTrue(is_array($result));
    }
}
