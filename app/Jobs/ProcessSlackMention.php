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
		elseif (stripos($event['text'], 'what is the weather for') !== FALSE) {
			$location = substr($event['text'], 39, -1);
      $response[] = $this->getWeather($location);
    }

    if (!empty($response)) {
      $payload = [
        'channel' => $event['channel'],
        'text' => implode("\n", $response),
      ];
      if (isset($event['thread_ts'])) {
        $payload['thread_ts'] = $event['thread_ts'];
      }
      $slack_client->chat->postMessage($payload);
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
      'what is the weather for <location>?' => 'Get the weather for a city or by zip code.',
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
	
	private function getWeather($location) {
		
	
		$key = env('WEATHER_API_KEY');
		
		if (is_numeric($location))
		{
			$url = 'http://api.openweathermap.org/data/2.5/weather?zip='.$location.'&units=imperial&appid='.$key;
		}
		else
		{
			$url = 'http://api.openweathermap.org/data/2.5/weather?q='.$location.'&units=imperial&appid='.$key;
		}
		
		if ($key != "")
		{
			$client = new Client();
			$response = $client->get($url, [
				'headers' => [
					'Accept' => 'application/json',
				],
			]);
			if ($response->getStatusCode() == 200) {
				$data = json_decode($response->getBody());
				$temp = $data->main->temp;
				$temp_min = $data->main->temp_min;
				$temp_max = $data->main->temp_max;
				$humidity = $data->main->humidity;
				$feels_like = $data->main->feels_like;
				
				$response = "The temperature is currently ".$temp."F. The humidity is ".$humidity."%. It feels like ".$feels_like."F. The low temperature for today is ".$temp_min."F. The high temperature for today is ".$temp_max."F.";
				
				return $response;
			}
		}
		else
		{
			return "Install Open Weather API Key in the .env file";
		}
		
  }

}
