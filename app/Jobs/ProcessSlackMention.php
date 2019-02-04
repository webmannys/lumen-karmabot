<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use wrapi\slack\slack as Slack;

class ProcessSlackMention extends Job {

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
   * @return void
   */
  public function handle() {
    $slack_client = new Slack(Config::get('services.slack.token'));

    $event = $this->payload['event'];
    $response = [];

    if (stripos($event['text'], 'help') !== FALSE) {
      $response[] = $this->getCommands();
    }
    elseif (stripos($event['text'], 'tell me a joke') !== FALSE) {
      $response[] = $this->getJoke();
    }
    elseif (stripos($event['text'], 'show me the karmas') !== FALSE) {
      $response[] = "I'm not ready!";
    }

    if (!empty($response)) {
      $slack_client->chat->postMessage([
        'channel' => $event['channel'],
        'text' => implode("\n", $response),
      ]);
    }
  }

  /**
   * Does a request for a joke at at the dad project.
   *
   * @return string
   *   A dad joke.
   */
  private function getCommands() {
    $commands = [
      'help' => "What you see before you.",
      'tell me a joke' => "Listen to some 'fun' jokes.",
      'show me the karmas' => "This shows who has karma (still TODO).",
    ];

    $response[] = 'Below are the commands you can use';
    foreach ($commands as $command => $description) {
      $response[] = "`" . $command . "`: " . $description;
    }
    return implode("\n", $response);
  }

  /**
   * Does a request for a joke at at the dad project.
   *
   * @return string
   *   A dad joke.
   */
  private function getJoke() {
    $client = new Client();
    $response = $client->get('https://icanhazdadjoke.com/', [
      'headers' => [
        'Accept' => 'application/json',
      ],
    ]);
    if ($response->getStatusCode() == 200) {
      $data = json_decode($response->getBody());
      return $data->joke;
    }

  }

}
