<?php

/** Exception: no name given for connexion.
 */
class NoNameGivenException extends AgoraUserException
{

	/** Constructor. */
	public function __construct()
	{
		parent::__construct( 'exceptions.nonamegiven' ) ;
	}

}
