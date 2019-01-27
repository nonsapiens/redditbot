<?php

return [

	'username' => env( 'REDDIT_USERNAME' ),
	'password' => env( 'REDDIT_PASSWORD' ),

	'app-id'     => env( 'REDDIT_APP_ID' ),
	'app-secret' => env( 'REDDIT_APP_SECRET' ),

	'useragent'  => env( 'REDDIT_BOT_USERAGENT', 'Yomo/LaravelRedditBot:v1.0' ),

];