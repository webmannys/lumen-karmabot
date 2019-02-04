<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSlackMention;
use App\Jobs\ProcessSlackMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    if (empty($payload['event'])
      || empty($payload['event']['type'])
      || empty($payload['event']['text'])) {
      // Do nothing
    }
    elseif ($payload['event']['type'] == 'message') {
      dispatch(new ProcessSlackMessage($payload));
    }
    elseif ($payload['event']['type'] == 'app_mention') {
      dispatch(new ProcessSlackMention($payload));
    }
    return response([
      'success' => TRUE,
    ]);
  }

}
