<?php

/** Format data for Ajax. */
class AjaxFormater
{

	/** Format data about a channel.
	 * @param Channel $channel The channel.
	 */
	public static function channel( Channel $channel )
	{
		return array(
			'id' => $channel->getId(),
			'name' => $channel->getName(),
			'title' => $channel->getTitle(),
			'type' => $channel->getType()
		) ;
	}

}
