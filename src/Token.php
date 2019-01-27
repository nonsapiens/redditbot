<?php


namespace Redditbot;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;


final class Token extends RedditApiHandler
{

	/** @var  string */
	private $token;

	/** @var  Carbon */
	private $expires;




	public function __construct ()
	{

		# Retrieve the token from cache, if possible
		if ( $token = Cache::get( 'yomo.redditbot.token' ) ) {

			$this->token   = $token[ 'token' ];
			$this->expires = Carbon::parse($token[ 'expires' ]);

		}
		else {
			$this->getApiToken();
		}

	}

	public function token ()
	{
		if ( !$this->expires || $this->expires->isPast() ) {
			$this->getApiToken();
		}

		return $this->token;
	}

	public function expires ()
	{
		if ( !$this->expires || $this->expires->isPast() ) {
			$this->getApiToken();
		}

		return $this->expires;
	}

	public function refresh ()
	{
		$this->getApiToken();
	}

	private function getApiToken ()
	{

		$login = $this->callReddit(
			self::ACCESS_TOKEN_URL,
			[
				'grant_type' => 'password',
				'username'   => config( 'redditbot.username' ),
				'password'   => config( 'redditbot.password' ),
			],
			[
				'username' => config( 'redditbot.app-id' ),
				'password' => config( 'redditbot.app-secret' ),
			] );

		if ( isset( $login[ 'body' ][ 'error' ] ) && $login[ 'body' ][ 'error' ] ) {
			throw new Exception( 'Authentication failed: ' . $login[ 'body' ][ 'error' ] );
		}

		$this->token   = $login[ 'body' ][ 'access_token' ];
		$this->expires = Carbon::now()->addSeconds( $login[ 'body' ][ 'expires_in' ] );

		Cache::put( 'yomo.redditbot.token', [ 'token' => $this->token, 'expires' => $this->expires->toDateTimeString() ], $login[ 'body' ][ 'expires_in' ] / 60 );
	}
}