# RedditBot

RedditBot is a simple-to-use Laravel 5.x library that wraps the Reddit API to make reading, posting and commenting on Reddit threads really easy!

This library was partially inspired by [Decronym](https://gist.github.com/Two9A/1d976f9b7441694162c8).

## Installation
Require this package with composer using the following command:
```bash
composer require yomo/redditbot
```

### Laravel 5.5 or higher

Execute:
```bash
php artisan vendor:publish --tag=redditbot
```


### Laravel 5.4 or lower
If you are running Laravel 5.4 and below, you need to add the service provider to the `providers` array in `config/app.php`
```php
Redditbot\Providers\RedditbotServiceProvider::class
```

Then execute:
```bash
php artisan vendor:publish --tag=yomo.realaddress
```

## Usage

This library assumes your bot has already been created through [Reddit's App Panel](https://www.reddit.com/prefs/apps/), through which you would need to register a "personal use script" application.  
More details on how to configure your bot can be found [via Reddit's Quick Start instructions](https://github.com/reddit-archive/reddit/wiki/OAuth2-Quick-Start-Example) 

Your .env file will require the following settings:

```ini
REDDIT_USERNAME
REDDIT_PASSWORD
REDDIT_APP_ID
REDDIT_APP_SECRET
```

### Retrieving comments from a subreddit and responding

Once this is configured, you can create an instance of the Reddit class with:

```php
$reddit = new Reddit();
$comments = $reddit->fetchComments('sheiseverywhere');
```

Comments are returned as a Collection, which you can easily iterate through using **foreach** or **->each**.
An example below shows how the bot replies to any instance of a specific phrase, using the "sinceLastCheck" function:

```php
$reddit = new Reddit();
$comments = $reddit->fetchComments('sheiseverywhere');

$comments->sinceLastCheck()->each(function($comment){

   if (strpos($comment->body, 'Suzuki Jimny') !== false) {
     $comment->reply('The Suzuki Jimny is the best off-roader ever built');   # This replies to the specific comment
     $comment->comment('Visit /r/jimny for more information'); # This submits a top-level post to the main thread
   }

});
```

## About the author

[**Stuart Steedman**](https://www.linkedin.com/in/stuart-steedman-b612a537/) is the head of development at [Yonder Media](http://www.yonder.co.za), a South African digital media agency operating out of Pretoria.
He specialises in PHP and Laravel development, and is a speaker at tech and development related events.
