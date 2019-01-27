<?php

namespace Redditbot\Responses;

/**
 * Class me
 *
 * @package     Redditbot\Responses
 *
 * @property-read   string              id
 * @property-read   string              name
 * @property-read   bool                isEmployee
 * @property-read   bool                seenLayoutSwitch
 * @property-read   bool                hasVisitedNewProfile
 * @property-read   bool                prefersNoProfanity
 * @property-read   bool                hasExternalAccount
 * @property-read   bool                prefGeopopular
 * @property-read   bool                seenRedesignModal
 * @property-read   bool                prefersShowTrending
 * @property-read   string              subreddit
 * @property-read   bool                isSponsor
 * @property-read   \Carbon\Carbon|null goldExpiration
 * @property-read   bool                hasGoldSubscription
 * @property-read   int                 numberOfFriends
 * @property-read   bool                hasAndroidSubscription
 * @property-read   bool                verified
 * @property-read   bool                newModmailExists
 * @property-read   bool                prefersAutoplay
 * @property-read   int                 coins
 * @property-read   bool                hasPaypalSubscription
 * @property-read   bool                hasSubscribedToPremium
 * @property-read   bool                hasStripeSubscription
 * @property-read   bool                seenPremiumAdblockModal
 * @property-read   bool                over18
 * @property-read   bool                isGold
 * @property-read   bool                isMod
 * @property-read   \Carbon\Carbon|null suspensionExpirationUTC
 * @property-read   bool                hasVerifiedEmail
 * @property-read   bool                isSuspended
 * @property-read   bool                prefersVideoAutoplay
 * @property-read   bool                inChat
 * @property-read   bool                inRedesignBeta
 * @property-read   string              iconImg
 * @property-read   bool                hasModMail
 * @property-read   bool                prefersNightmode
 * @property-read   string              oauthClientId
 * @property-read   bool                hideFromRobots
 * @property-read   int                 linkKarma
 * @property-read   bool                forcePasswordReset
 * @property-read   int                 inboxCount
 * @property-read   bool                prefersTopKarmaSubreddits
 * @property-read   bool                hasMail
 * @property-read   bool                prefersShowSnoovatar
 * @property-read   int                 prefClickgadget
 * @property-read   \Carbon\Carbon|null created
 * @property-read   \Carbon\Carbon|null createdUtc
 * @property-read   int                 goldCreddits
 * @property-read   bool                hasIosSubscription
 * @property-read   bool                prefersShowTwitter
 * @property-read   bool                inBeta
 * @property-read   int                 commentKarma
 * @property-read   bool                hasSubscribed
 * @property-read   bool                seenSubredditChatFtux
 */
class Me extends ApiResponse
{

	protected $dateFields = [ 'gold_expiration', 'suspension_expiration_utc', 'created', 'created_utc' ];

	protected $mapFields = [
		'num_friends'               => 'number_of_friends',
		'pref_no_profanity'         => 'prefers_no_profanity',
		'pref_show_trending'        => 'prefers_show_trending',
		'pref_geopopular'           => 'prefers_geopopular',
		'pref_autoplay'             => 'prefers_autoplay',
		'pref_video_autoplay'       => 'prefers_video_autoplay',
		'pref_nightmode'            => 'prefers_nightmode',
		'pref_top_karma_subreddits' => 'prefers_top_karma_subreddits',
		'pref_show_snoovatar'       => 'prefers_show_snoovatar',
		'pref_show_twitter'         => 'prefers_show_twitter',
	];

}