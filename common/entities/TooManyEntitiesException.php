<?php

class TooManyEntitiesException extends AgoraUserException
{
	public function __construct( $type )
	{
		parent::__construct(
			"exceptions.$type.toomanyentities"
		);
	}
}
