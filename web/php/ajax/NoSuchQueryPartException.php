<?php

/** Exception: call of nonexistant query part
 */
class NoSuchQueryPartException extends AgoraUserException
{

	/** Constructor
	 * @param string $partName Unfound part name.
	 */
	public function __construct( $partName )
	{
		parent::__construct( 'exceptions.nosuchquerypart', array( 'partname' => $partName ) ) ;
	}

}
