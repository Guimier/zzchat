<?php

class PostContentTooLongException extends AgoraUserException
{
	public function __construct( $percentage )
	{
		parent::__construct(
			"exceptions.postcontenttoolong",
			array( 'percentage' => $percentage )
		);
	}
}
