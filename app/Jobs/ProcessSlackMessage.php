<?php

namespace App\Jobs;

class ProcessSlackMessage extends Job {

  protected $payload;

  /**
   * Create a new job instance.
   *
   * @param array $payload
   *   The payload from the event.
   *
   * @return void
   */
  public function __construct(array $payload) {
    $this->payload = $payload;
  }

  /**
   * Execute the job.
   *
   * @param array $payload
   *   The payload from the event.
   *
   * @return void
   */
  public function handle() {
    var_dump($this->payload);
    throw new \RuntimeException('I am still debugging');
  }

}
