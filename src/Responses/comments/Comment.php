<?php

namespace Redditbot\Responses\comments;

use Redditbot\RedditApiHandler as Api;
use Redditbot\Responses\ApiResponse;

/**
 * Class Comment
 *
 * @package     Redditbot\Responses\comments
 *
 * @property-read string                    id
 * @property-read       string              authorFlairBackgroundColor
 * @property-read       string              subredditId
 * @property-read       \Carbon\Carbon|null approvedAtUtc
 * @property-read       bool|\Carbon\Carbon edited
 * @property-read       string              modReasonBy
 * @property-read       string              bannedBy
 * @property-read       string              authorFlairType
 * @property-read       string              removalReason
 * @property-read       string              linkId
 * @property-read       string              authorFlairTemplateId
 * @property-read       bool                likes
 * @property-read       string|mixed        $replies
 * @property-read       array               $userReports
 * @property-read bool                      saved
 * @property-read \Carbon\Carbon|null       bannedAtUtc
 * @property-read string                    modReasonTitle
 * @property-read int                       gilded
 * @property-read bool                      archived
 * @property-read bool                      noFollow
 * @property-read string                    author
 * @property-read int                       numberOfComments
 * @property-read bool                      canModPost
 * @property-read bool                      sendReplies
 * @property-read string                    parentId
 * @property-read int                       score
 * @property-read string                    authorFullname
 * @property-read bool                      over18
 * @property-read string                    approvedBy
 * @property-read string                    modNote
 * @property-read int                       controversiality
 * @property-read string                    body
 * @property-read string                    linkTitle
 * @property-read int                       downs
 * @property-read string                    authorFlairCssClass
 * @property-read string                    name
 * @property-read \Carbon\Carbon|null       created
 * @property-read string                    authorPatreonFlair
 * @property-read bool                      collapsed
 * @property-read string                    authorFlairRichtext
 * @property-read bool                      isSubmitter
 * @property-read string                    bodyHtml
 * @property-read array                     gildings
 * @property-read string                    collapsedReason
 * @property-read bool                      stickied
 * @property-read bool                      canGild
 * @property-read bool                      removed
 * @property-read bool                      approved
 * @property-read string                    authorFlairTextColor
 * @property-read bool                      scoreHidden
 * @property-read string                    permalink
 * @property-read int                       numberOfReports
 * @property-read string                    linkPermalink
 * @property-read array                     reportReasons
 * @property-read string                    linkAuthor
 * @property-read string                    subreddit
 * @property-read string                    authorFlairText
 * @property-read string                    linkUrl
 * @property-read bool                      spam
 * @property-read \Carbon\Carbon|null       createdUtc
 * @property-read string                    subredditNamePrefixed
 * @property-read bool|mixed                distinguished
 * @property-read bool                      ignoreReports
 * @property-read array                     modReports
 * @property-read bool                      quarantine
 * @property-read string                    subredditType
 * @property-read int                       ups
 */
class Comment extends ApiResponse
{

	protected $dateFields = [ 'approved_at_utc', 'banned_at_utc', 'created', 'created_utc' ];

	protected $mapFields = [
		'num_comments' => 'number_of_comments',
		'num_reports'  => 'number_of_reports',
	];

	/**
	 * @return string
	 */
	public function author ()
	{
		return $this->author;
	}

	/**
	 * @return string
	 */
	public function body ()
	{
		return $this->body;
	}

	/**
	 * @return \Carbon\Carbon|null
	 */
	public function created ()
	{
		return $this->createdUtc;
	}

	/**
	 * @return array
	 */
	public function thread ()
	{
		list( $type, $id ) = explode( '_', $this->linkId );

		return [
			'type' => $type,
			'id'   => base_convert( $id, 36, 10 ),
		];
	}


	/**
	 * Posts a comment below this specific comment (a reply)
	 *
	 * @param string $body
	 *
	 * @return \Redditbot\Responses\comments\Comment
	 */
	public function reply ( $body )
	{

		$response = Api::send( Api::API_BASE_URL . '/api/comment', $this->token, [
			'api_type' => 'json',
			'thing_id' => $this->name,
			'text'     => $body,
		] );

		return ApiResponse::getInstance( 'comments\Comment', $response[ 'body' ][ 'json' ][ 'data' ][ 'things' ][ 0 ][ 'data' ]
			, null, $this->token );

	}


	/**
	 * Posts a comment to the main thread (top-level comment)
	 *
	 * @param string $body
	 *
	 * @return \Redditbot\Responses\comments\Comment
	 */
	public function comment ( $body )
	{
		$response = Api::send( Api::API_BASE_URL . '/api/comment', $this->token, [
			'api_type' => 'json',
			'thing_id' => $this->linkId,
			'text'     => $body,
		] );

		return ApiResponse::getInstance( 'comments\Comment', $response[ 'body' ][ 'json' ][ 'data' ][ 'things' ][ 0 ][ 'data' ]
			, null, $this->token );
	}


}