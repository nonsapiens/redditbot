<?php

namespace Redditbot\Facades;


use Illuminate\Support\Facades\Facade;


class Reddit extends Facade
{

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return \Redditbot\Reddit::class;
	}

}