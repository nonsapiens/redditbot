<?php

namespace Redditbot;


use Redditbot\Responses\ApiResponse;


class Reddit extends RedditApiHandler
{

	/** @var \Redditbot\Token */
	protected $token;


	public function __construct ()
	{
		$this->token = new Token();
	}


	/**
	 * @return \Redditbot\Responses\me
	 */
	public function me ()
	{
		return ApiResponse::getInstance( __FUNCTION__, $this->send( self::API_BASE_URL . '/api/v1/me', $this->token ), 'body', $this->token );
	}


	public function fetchComments ( $subreddit, $sortBy = 'new', $limit = 100 )
	{

		return ApiResponse::getInstance( 'comments', $this->send( self::API_BASE_URL . '/r/' . $subreddit
		                                                          . '/comments/?' . http_build_query( [
			                                                                                              'cb'    => time(),
			                                                                                              'sort'  => $sortBy,
			                                                                                              'limit' => $limit,
		                                                                                              ] ), $this->token ), null, $this->token );
	}


}