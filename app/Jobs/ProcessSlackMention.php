<?php

namespace App\Jobs;

use App\Models\Karma;
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
    $slack_client_class = Config::get('services.slack.client');
    $slack_client = new $slack_client_class(Config::get('services.slack.token'));

    $event = $this->payload['event'];
    $response = [];

    // This could potentially be handled by event listeners in the future.
    // For now, an array approach is just fine.

    if (stripos($event['text'], 'help') !== FALSE) {
      $response[] = $this->getCommands();
    }
    elseif (stripos($event['text'], 'tell me a joke') !== FALSE) {
      $response[] = $this->getJoke();
    }
    elseif (stripos($event['text'], 'show me the karmas') !== FALSE) {
      $response[] = $this->getKarmaList();
    }
    elseif (stripos($event['text'], 'who are you?') !== FALSE) {
      $response[] = $this->shareInfo();
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
      'show me the karmas' => "This shows who has karma.",
      'who are you?' => 'Learn more about this slackbot.',
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

  /**
   * Get a list of all users with karma points.
   *
   * @return string
   *   The karma list.
   */
  private function getKarmaList() {
    $karma_users = Karma::orderBy('points', 'desc')->get();
    $response = [];
    foreach ($karma_users as $karma_user) {
      $response[] = "<@{$karma_user->handle}>: {$karma_user->points}";
    }
    return implode("\n", $response);
  }

  private function shareInfo() {
    return 'My name is slackbot. I am built using PHP and the Lumen Framework. You can clone me at https://gitlab.com/btmash/lumen-karmabot.';
  }

}
