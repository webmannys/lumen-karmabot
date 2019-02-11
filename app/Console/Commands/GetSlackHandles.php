<?php

namespace App\Console\Commands;

use App\Models\SlackUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class GetSlackHandles extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'slack:get-users';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Retrieves all users from slack.';

  /**
   * Slack client.
   *
   * @var \wrapi\slack\slack;
   */
  protected $slack_client;

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    $slack_client_class = Config::get('services.slack.client');
    $this->slack_client = new $slack_client_class(Config::get('services.slack.token'));
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $slack_users = $this->slack_client->users->list();
    $bar = $this->output->createProgressBar(count($slack_users));
    $bar->start();
    foreach ($slack_users['members'] as $slack_user) {
      $bar->advance();
      if ($slack_user['deleted']) {
        $slack_member = SlackUser::where(['uid' => $slack_user['id']]);
        if ($slack_member) {
          $slack_member->delete();
          continue;
        }
      }
      $slack_member = SlackUser::firstOrNew([
        'uid' => $slack_user['id'],
      ]);
      $slack_member->name = $slack_user['name'] ?? '';
      $slack_member->real_name = $slack_user['real_name'] ?? '';
      $slack_member->profile_real_name = $slack_user['profile']['real_name'] ?? '';
      $slack_member->profile_real_name_normalized = $slack_user['profile']['real_name_normalized'] ?? '';
      $slack_member->profile_display_name = $slack_user['profile']['display_name'] ?? '';
      $slack_member->profile_display_name_normalized = $slack_user['profile']['display_name_normalized'] ?? '';
      $slack_member->save();
    }
    $bar->finish();
    $this->output->newLine(2);
    $this->info('Slack Member import complete!');
  }

}
