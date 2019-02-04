<?php

namespace App\Jobs;

use App\Models\Karma;
use Illuminate\Support\Facades\Config;
use wrapi\slack\slack as Slack;

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
   * @return void
   */
  public function handle() {
    $slack_client = new Slack(Config::get('services.slack.token'));

    $event = $this->payload['event'];
    $response = [];
    // Only process messages that were added the first time around and not empty
    // strings.
    var_dump($event['type']);
    if ($event['type'] === 'message'
      && !empty ($message['text'])
      && empty($message['edited'])) {
      $message = $event['text'];
      if ((strpos($message, '++') !== FALSE) || (strpos($message, '--') !== FALSE)) {
        //Yeah, an additional line. But let's not touch $message anytime
        $subject = $message;
        //Looks for @someone++ or @someone--
        $pattern = '/\<\@([^\>]*)\>\:?\s?([\+\-][\+\-])/';
        //Get all such matches
        preg_match_all($pattern, $subject, $matches);
        foreach ($matches[1] as $key => $handle) {
          if ($handle == $event['user']) {
            $response[] = 'Nice try, my friend. Ask a friend to help you :)';
            continue;
          }
          if ($matches[2][$key] == '--') {
            $response [] = $this->slackUpdateAndShow($handle, -1);
          }
          elseif ($matches[2][$key] == '++') {
            $response [] = $this->slackUpdateAndShow($handle, +1);
          }
        }
      }
    }
    if (!empty($response)) {
      $slack_client->chat->postMessage([
        'channel' => $event['channel'],
        'text' => implode("\n", $response),
      ]);
    }
  }

  /**
   * Helper function to update and output points.
   *
   * @param string $handle
   *  The username to add/update.
   * @param int $karma
   *  The points to add/remove.
   *
   * @return string
   *   The handle and the number of points that have accumulated.
   */
  private function slackUpdateAndShow($handle, $karma) {
    $karma_user = Karma::firstOrNew(['handle' => $handle]);
    $karma_user->points += $karma;
    $karma_user->save();
    return "<@{$handle}> now has {$karma_user->points} Karma points!";
  }

}
