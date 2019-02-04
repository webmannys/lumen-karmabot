<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSlackMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Karma;

class KarmaController extends Controller {

  /**
   * Queues request and responds back with 200.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return Response
   */
  public function queueAndRespond(Request $request) {
    $payload = $request->all();
    dispatch(new ProcessSlackMessage($payload));
    return response([
      'success' => TRUE,
    ]);
  }

  /**
   * Create/update karma points for a given handle.
   *
   * @param  Request $request
   *
   * @return Response
   */
  public function slackEvent(Request $request) {
    $message = $request->input('text');
    $response = [];
    if ((strpos($message, '++') !== FALSE) || (strpos($message, '--') !== FALSE)) {
      //Yeah, an additional line. But let's not touch $message anytime
      $subject = $message;
      //Looks for @someone++ or @someone--
      $pattern = '/\<\@([^\>]*)\>\:?\s?([\+\-][\+\-])/';
      //Get all such matches
      preg_match_all($pattern, $subject, $matches);
      foreach ($matches[1] as $key => $handle) {
        // @TODO: Need to figure out if user doing karma is same as
        // handle getting karma.
        if ($matches[2][$key] == '--') {
          $response [] = $this->slackUpdateAndShow($handle, -1);
        }
        if ($matches[2][$key] == '++') {
          $response [] = $this->slackUpdateAndShow($handle, +1);
        }
      }
    }

    return response([
      'text' => implode("\n", $response),
    ]);
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
