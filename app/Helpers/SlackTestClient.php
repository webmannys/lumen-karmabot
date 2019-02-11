<?php

namespace App\Helpers;


class SlackTestClient {

  /**
   * The private token to connect to and use with Slack.
   *
   * @var string
   */
  private $token;

  private $endpoint;

  public function __construct($token) {
    $this->token = $token;
    $this->endpoint = [];
  }

  public function __get($name) {
    $this->endpoint[] = $name;
    return $this;
  }

  public function postMessage($params) {
    if ($this->endpoint[0] === 'chat') {
      return ['status' => 200];
    }
  }

  public function list() {
    if ($this->endpoint[0] == 'users') {
      return [
        'ok' => TRUE,
        'members' => [
          [
            'id' => 'USLACKBOT',
            'team_id' => 'my-team-id',
            'name' => 'slackbot',
            'deleted' => FALSE,
            'real_name' => 'Slackbot',
            'profile' => [
              'title' => 'botman',
              'phone' => '',
              'skype' => '',
              'real_name' => 'Slack Bot',
              'real_name_normalized' => 'Slack Bot',
              'display_name' => 'slackbot',
              'display_name_normalized' => 'slackbot',
            ],
            'is_admin' => FALSE,
            'is_owner' => FALSE,
            'is_primary_owner' => FALSE,
            'is_restricted' => FALSE,
            'is_ultra_restricted' => FALSE,
            'is_bot' => FALSE,
            'is_app_user' => FALSE,
            'updated' => 0,
          ],
          [
            'id' => 'MY-SLACK-ID1',
            'team_id' => 'my-team-id',
            'name' => 'btmash',
            'deleted' => FALSE,
            'real_name' => 'Ashok Modi',
            'profile' => [
              'title' => '',
              'phone' => '',
              'skype' => '',
              'real_name' => 'Ashok Mod',
              'real_name_normalized' => 'Ashok Modi',
              'display_name' => 'BTMash',
              'display_name_normalized' => 'BTMash',
            ],
            'is_admin' => TRUE,
            'is_owner' => TRUE,
            'is_primary_owner' => FALSE,
            'is_restricted' => FALSE,
            'is_ultra_restricted' => FALSE,
            'is_bot' => FALSE,
            'is_app_user' => FALSE,
            'updated' => 0,
          ],
          [
            'id' => 'MY-SLACK-ID2',
            'team_id' => 'my-team-id',
            'name' => 'csabian',
            'deleted' => FALSE,
            'real_name' => 'Chris Sabian',
            'profile' => [
              'title' => '',
              'phone' => '',
              'skype' => '',
              'real_name' => 'Chris Sabian',
              'real_name_normalized' => 'Chris Sabian',
              'display_name' => 'The Negotiator',
              'display_name_normalized' => 'The Negotiator',
            ],
            'is_admin' => FALSE,
            'is_owner' => FALSE,
            'is_primary_owner' => FALSE,
            'is_restricted' => FALSE,
            'is_ultra_restricted' => FALSE,
            'is_bot' => FALSE,
            'is_app_user' => FALSE,
            'updated' => 0,
          ],
        ],
      ];
    }
    return FALSE;
  }

}