<?php


namespace Redditbot\Responses;


use Carbon\Carbon;
use Redditbot\Token;

abstract class ApiResponse
{

	protected $dateFields     = [];
	protected $mapFields      = [];
	protected $instantiatable = [];

	/** @var  Token */
	protected $token;

	public static function getInstance ( $type, $data, $key = 'body', Token $token )
	{
		$class = "\Redditbot\Responses\\$type";
		if ( class_exists( $class ) ) {
			$instance = new $class( $data, $key, $token );

			return $instance;
		}
	}


	public function __construct ( array $data, $collectionKey = 'body', Token $token )
	{
		$this->token = $token;
		$collection  = $collectionKey ? $data[ $collectionKey ] : $data;

		collect( $collection )->each( function ( $item, $key ) {

			$instance = $item;

			if ( array_search( $key, $this->mapFields ) !== false ) {
				$key = $this->mapFields[ $key ];
			}

			if ( array_search( $key, $this->dateFields ) !== false && $item ) {
				$instance = Carbon::createFromTimestamp( $item );
			}

			if ( is_array( $item ) ) {
				collect( $this->instantiatable )->each( function ( $iv, $ik ) use ( &$item, $key ) {

					$parts = explode( '.', $ik );

					if ( $plucked = array_pluck( $item[ $parts[ 1 ] ], $parts[ 0 ] ) ) {
						foreach ( $plucked as $pluck ) {
							$instance[] = self::getInstance( $iv, $pluck, null );
						}
					}

				} );
			}

			switch ( $key ) {

				case 'id' :
					$instance = base_convert( $instance, 36, 10 );
					break;


			}

			$key        = camel_case( $key );
			$this->$key = $instance;

		} );

	}


}