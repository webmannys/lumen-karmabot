<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EntrypointTest extends TestCase {

  /**
   * Tests that basic get request will return back laravel version.
   *
   * @return void
   */
  public function testGet() {
    $this->get('/')
      ->seeJson([
        'version' => $this->app->version(),
      ]);
  }

  /**
   * Test that post request with bad credentials will yield 403.
   */
  public function testAccessDeniedPost() {
    $this->json('POST', '/', [])
      ->seeStatusCode(403)
      ->seeJson([
        'status' => 'unauthorized'
      ]);

    $this->json('POST', '/', [
      'type' => 'url_verification',
      'token' => 'badtoken',
      'challenge' => 'someabstractverifiedtoken',
    ])->seeStatusCode(401)
      ->seeJson([
        'status' => 'unauthorized'
      ]);
  }

  /**
   * Test that post request with valid credentials will return back challenge.
   */
  public function testChallengeVerified() {
    $this->json('POST', '/', [
      'type' => 'url_verification',
      'token' => 'gitlabdebuggingtoken',
      'challenge' => 'someabstractverifiedtoken',
    ])->seeStatusCode(200)
      ->seeJson([
        'challenge' => 'someabstractverifiedtoken'
      ]);
  }

  /**
   * Test that message event is created
   */
  public function testMessageJobCreated() {
    $this->expectsJobs(['App\Jobs\ProcessSlackMessage']);
    $this->json('POST', '/', [
      'token' => 'gitlabdebuggingtoken',
      'type' => 'message',
      'event' => [
        'type' => 'message',
        'channel' => '#general',
        'text' => 'Just some general text',
        'user' => 'user1',
      ],
    ])->seeStatusCode(200)
      ->seeJson([
        'success' => TRUE,
      ]);
  }

  /**
   * Test that message event is created
   */
  public function testAppMentionJobCreated() {
    $this->expectsJobs(['App\Jobs\ProcessSlackMention']);
    $this->json('POST', '/', [
      'token' => 'gitlabdebuggingtoken',
      'type' => 'message',
      'event' => [
        'type' => 'app_mention',
        'channel' => '#general',
        'text' => '<@karmabot> Just some general text',
        'user' => 'user1',
      ],
    ])->seeStatusCode(200)
      ->seeJson([
        'success' => TRUE,
      ]);
  }

}
