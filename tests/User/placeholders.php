<?php
// @codeCoverageIgnoreStart

class NameExclusions
{
	public function checkName( $name )
	{
		if ( $name === 'unallowed' )
		{
			throw new NotAuthorizedNameException( $name ) ;
		}
	}
}
