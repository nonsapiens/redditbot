<?php

namespace Redditbot\Responses;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Redditbot\Token;

class Comments extends Collection
{

	/** @var  \Redditbot\Responses\comments\Comment[]|\Illuminate\Support\Collection */
	protected $items;

	/** @var  Token */
	protected $token;

	public function __construct ( array $data, $collectionKey = 'body', Token $token )
	{
		$this->token = $token;

		if ( array_key_exists( 'body', $data ) && $collection = @$data[ 'body' ][ 'data' ][ 'children' ] ) {
			$items = [];

			collect( array_pluck( $collection, 'data' ) )->each( function ( $comment ) use ( &$items, $token ) {
				$items[ $comment[ 'id' ] ] = ApiResponse::getInstance( 'comments\Comment', $comment, null, $token );
			} );

			$this->items = $items;
		}
		else {
			$this->items = $data;
		}
	}


	/**
	 * Returns new comments since the last time this function was run
	 *
	 * @return \Redditbot\Responses\comments\Comment[]|Collection
	 */
	public function sinceLastCheck ()
	{
		$lastRun  = Carbon::parse( Cache::get( 'yomo.redditbot.lastrun', Carbon::now()->subDay( 1 ) ) );
		$newItems = collect();

		self::each( function ( $comment ) use ( $lastRun, &$newItems ) {

			/** @var \Redditbot\Responses\comments\Comment $comment */
			if ( ( $comment->edited && Carbon::createFromTimestamp( $comment->edited )->greaterThan( $lastRun ) )
			     || $comment->createdUtc->greaterThan( $lastRun ) ) {

				# Don't react to this bot's own submissions
				if ( $comment->author != config( 'redditbot.username' ) ) {
					$newItems->put( $comment->id, $comment );
				}

			}
		} );

		Cache::forever( 'yomo.redditbot.lastrun', Carbon::now()->toDateTimeString() );

		return [

			'lastRun'  => $lastRun->toDateTimeString(),
			'comments' => $newItems,
			'count'    => $newItems->count(),

		];
	}


}