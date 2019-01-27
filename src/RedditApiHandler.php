<?php


namespace Redditbot;


use Exception;

abstract class RedditApiHandler
{


	const ACCESS_TOKEN_URL = 'https://www.reddit.com/api/v1/access_token';
	const API_BASE_URL     = 'https://oauth.reddit.com';


	protected static function callReddit ( $url, $data, $auth = null, $headers = [] )
	{
		$headers[ 'Expect' ] = '';
		$curlheaders         = [];
		foreach ( $headers as $k => $v ) {
			$curlheaders[] = "{$k}: {$v}";
		}
		$c      = curl_init();
		$params = [
			CURLOPT_URL            => $url,
			CURLOPT_HEADER         => true,
			CURLOPT_VERBOSE        => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_USERAGENT      => config( 'redditbot.useragent' ),
			CURLOPT_SSLVERSION     => 4,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER     => $curlheaders,
		];
		if ( $data ) {
			$params += [
				CURLOPT_POST          => true,
				CURLOPT_POSTFIELDS    => http_build_query( $data ),
				CURLOPT_CUSTOMREQUEST => 'POST',
			];
		}
		if ( $auth ) {
			$params += [
				CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
				CURLOPT_USERPWD  => "{$auth['username']}:{$auth['password']}",
			];
		}
		curl_setopt_array( $c, $params );
		$r          = curl_exec( $c );
		$headersize = curl_getinfo( $c, CURLINFO_HEADER_SIZE );
		curl_close( $c );
		$headers = [];
		$header  = substr( $r, 0, $headersize );
		$body    = substr( $r, $headersize );
		foreach ( explode( "\r\n", $header ) as $i => $line ) {
			if ( $i === 0 ) {
				$headers[ '_code' ] = $line;
			}
			else {
				if ( strlen( trim( $line ) ) && strpos($line, ':') !== false ) {
					list( $k, $v ) = explode( ':', $line );
					$headers[ trim( $k ) ] = trim( $v );
				}
			}
		}

		return [
			'headers' => $headers,
			'body'    => json_decode( trim( $body ), true ),
		];
	}


	public static function send ( $url, Token $token, $data = [] )
	{

		$result = self::callReddit( $url, $data, null, [
			'Authorization' => 'bearer ' . $token->token(),
		] );
		if ( isset( $result[ 'body' ][ 'error' ] ) && $result[ 'body' ][ 'error' ] ) {

			if ( in_array( $result[ 'body' ][ 'error' ], [ 401, 'invalid_grant' ] ) ) {

				$token->refresh();

				return self::send( $url, $token, $data );
			}
			else {
				throw new Exception( 'Server error: ' . $result[ 'body' ][ 'error' ] );
			}
		}
		else if ( isset( $result[ 'body' ][ 'json' ], $result[ 'body' ][ 'json' ][ 'errors' ] ) ) {
			if ( count( $result[ 'body' ][ 'json' ][ 'errors' ] ) ) {
				throw new Exception( json_encode( $result[ 'body' ][ 'json' ][ 'errors' ] ) );
			}
		}

		return $result;
	}

}