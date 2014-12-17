<?php

/** Input HTML contains an illegal attribute. */
class EmptyMessageException extends AgoraUserException
{

	/** Constructor. */
	public function __construct()
	{
		parent::__construct( 'exceptions.emptymessage' ) ;
	}

}
