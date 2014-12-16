<?php

class ContainsIllegalCharacterException extends AgoraUserException
{
	public function __construct( $type, $name )
	{
		parent::__construct(
			"exceptions.$type.containsillegalcharacter",
			array( 'name' => $name )
		);
	}
}
