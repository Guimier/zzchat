<?php

/** Input HTML contains an illegal element. */
class IllegalElementException extends AgoraUserException
{

	/** Constructor.
	 * @param string $nodeName The illegal element’s name.
	 */
	public function __construct( $nodeName )
	{
		parent::__construct(
			'exceptions.illegalelement',
			array( 'element' => $nodeName )
		) ;
	}

}
