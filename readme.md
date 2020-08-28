# Slack Karmabot Built on Lumen

This provides a simple karmabot that is built on Lumen. Since we are only returning JSON and requests are not exactly cached, we queue up the requests and process them afterwards. Simple approach.

At some point in its life, it will also have testing. However, I need to figure out how to do that in Laravel/Lumen so this is not yet ready.

### Config Variables

`ALLOWED_SLACK_PROCESSING_TOKENS`: This consists of tokens that will filter out to make sure the requests are only coming in from the right intent places.
`SLACK_OAUTH_ACCESS_TOKEN`: The oauth token is needed to connect to slack and get list of users, send message to channel.

### Setup on Slack

You need to enable the following features:

- Incoming Webhooks
- OAuth and Permissions
- Event Subscriptions
- Bot Users

You need to create a bot with the following oauth privileges:

- `bot`
- `chat:write:bot`
- `incoming-webhook`
- `users:read`

You also need to subscribe the bot to the following event subscriptions:

- `app_mention`
- `message.channels`
- `message.groups` (this one is optional and really only use the bot in private groups).

To setup weather feature:

- Goto https://openweathermap.org/ and get an API key.
- Add the key to WEATHER_API_KEY in the .env file.

## About Lumen
[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
