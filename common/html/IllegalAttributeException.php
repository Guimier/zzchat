<?php

/** Input HTML contains an illegal attribute. */
class IllegalAttributeException extends AgoraUserException
{

	/** Constructor.
	 * @param string $nodeName The element’s name.
	 * @param string $attrName The illegal attribute’s name
	 */
	public function __construct( $nodeName, $attrName )
	{
		parent::__construct(
			'exceptions.illegalattribute',
			array( 'element' => $nodeName, 'attribute' => $attrName )
		) ;
	}

}
