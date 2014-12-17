<?php

class HTML
{

	/** Allowed nodes.
	 * Associative array indexed by tag names.
	 * 
	 * Values are associative arrays whose indexes are attribute names
	 * and values are regular expressions for allowed parameters
	 * (passed to preg_match).
	 */
	private static $allowed = array(
		'p' => array(),
		'strong' => array(),
		'strike' => array(),
		'em' => array(),
		'a' => array(
			'href' => '#^https?://#'
		),
		'img' => array(
			'src' => '#^web/img/smiley-\w+\.png$#'
		)
	) ;
	
	private static function checkNode( DOMElement $node )
	{
		$name = $node->tagName ;

		if ( ! array_key_exists( $name, self::$allowed ) )
		{
			throw new IllegalElementException( $name ) ;
		}
		
		$alAttrs = self::$allowed[$name] ;
		
		foreach ( $node->attributes as $key => $attr )
		{
			if ( ! array_key_exists( $key, $alAttrs ) )
			{
				throw new IllegalAttributeException( $name, $key ) ;
			}
			else if ( ! preg_match( $alAttrs[$key], $attr->nodeValue ))
			{
				throw new IllegalAttributeValueException( $name, $key,  $attr->nodeValue ) ;
			}
		}
	}

	private static function checkChildren( DOMElement $elem = null )
	{
		if ( $elem !== null )
		{
			$nodes = $elem->getElementsByTagName( '*' ) ;
			foreach ( $nodes as $node )
			{
				self::checkNode( $node ) ;
			}
		}
	}

	public static function checkInput( $html )
	{
		$dd = new DOMDocument() ;
		$dd->loadHTML( $html ) ;
		self::checkChildren( $dd->getElementsByTagName( 'head' )->item( 0 ) ) ;
		self::checkChildren( $dd->getElementsByTagName( 'body' )->item( 0 ) ) ;
	}

}
