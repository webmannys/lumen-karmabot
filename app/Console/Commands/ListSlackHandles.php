<?php

namespace App\Console\Commands;

use App\Models\SlackUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class ListSlackHandles extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'slack:list-users';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'List all users and their updated date from slack.';

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
    foreach ($slack_users['members'] as $slack_user) {
      $this->output->writeln($slack_user['name'] . ',');
    }
    $this->output->newLine(2);
    $this->info('Slack Member import complete!');

//    The below is not currently running as the bot requires the right permissions.
//    $slack_channels = $this->slack_client->conversations->list();
//    foreach ($slack_channels as $slack_channel) {
//      $this->output->writeln($slack_channel['id'] . ' - ' . $slack_channel['name']);
//    }
  }

}
