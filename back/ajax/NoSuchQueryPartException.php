<?php

/** Exception: call of nonexistant query part
 */
class NoSuchQueryPartException extends Exception
{

	/** Constructor
	 * @param string $partName Unfound part name.
	 */
	public function __construct( $partName )
	{
		parent::__construct( "$partName query part does not exist" ) ;
	}

}
