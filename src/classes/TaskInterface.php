<?php

namespace Spider;

interface TaskInterface
{
  /**
   * Does the job and returns subsequent tasks if occurs.
   * @return Task[]
   */
  public function process();
}

