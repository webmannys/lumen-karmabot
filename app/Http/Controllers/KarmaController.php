<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Karma;

class KarmaController extends Controller {

  /**
   * Create/update karma points for a given handle.
   *
   * @param  Request  $request
   * @return Response
   */
  public function slackEvent(Request $request) {
    if ($request->has('challenge')) {
      return response()->json([
        'challenge' => $request->input['challenge'],
      ]);
    }


  }
}
