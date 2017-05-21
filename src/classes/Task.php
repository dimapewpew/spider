<?php

namespace Spider;

use DirectoryIterator;

class Task implements TaskInterface
{
  private $details;

  /**
   * Creates the task
   * @param array $details task details
   */
  public function __construct($details = [])
  {
    $this->details = $details;
  }

  /**
   * Does the job
   * @return Task[] optionally returns the array of subsequent tasks
   */
  public function process()
  {
    $path = $this->details['path'];
    $pattern = $this->details['pattern'];
    $tasks = [];

    $dir = new DirectoryIterator($path);
    foreach ($dir as $item)
    {
      $itemName = $item->getFilename();
      $itemPath = $item->getPathname();

      if ($item->isDot()) {
        continue;
      }

      if ($item->isDir()) {
        $tasks[] = new Task([
          'path' => $itemPath,
          'pattern' => $pattern,
        ]);
        continue;
      }

      if ($item->isFile() && preg_match($pattern, $itemName)) {
        $this->processFile($itemPath);
      }
    }

    return $tasks;
  }

  /**
   * Deletes the file
   * @param string $path path to file to be processed
   */
  private function processFile($path)
  {
    sleep(1);
    $pid = getmypid();
    file_put_contents('work.log', "PID: $pid UNLINK: $path\n", FILE_APPEND);
  }
}

