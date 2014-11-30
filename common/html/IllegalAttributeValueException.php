<?php

/** An attribute has invalid content in HTML input.  */
class IllegalAttributeValueException extends AgoraUserException
{

	/** Constructor.
	 * @param string $nodeName The element’s name.
	 * @param string $attrName The attribute’s name.
	 * @param string $attrVal  The attribute’s content
	 */
	public function __construct( $nodeName, $attrName, $attrVal )
	{
		parent::__construct(
			'exceptions.illegalattributevalue',
			array(
				'element' => $nodeName,
				'attribute' => $attrName,
				'value' => $attrVal
			)
		) ;
	}

}
