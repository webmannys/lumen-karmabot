<?php

namespace App\Http\Middleware;

use Closure;

class KarmaMiddleware {

  /**
   * The allowed tokens.
   *
   * @var array
   */
  protected $allowed_tokens;

  /**
   * Create a new middleware instance.
   *
   * @return void
   */
  public function __construct() {
    $this->allowed_tokens = explode(',', env('ALLOWED_SLACK_PROCESSING_TOKENS'));
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   *
   * @return mixed
   */
  public function handle($request, Closure $next) {
    if ($request->has('token') && in_array($request->input('token'), $this->allowed_tokens)) {
      if ($request->has('challenge') && $request->input('type') == 'url_verification') {
        return response()->json([
          'challenge' => $request->input('challenge'),
        ]);
      }
      return $next($request);
    }
    return response('Unauthorized.', 401);
  }

}
