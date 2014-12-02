<?php
/** The context of the call. */
abstract class Context
{

	/** The canonical instance. */
	private static $canonical = null ;

	/** Get the canonical instance. */
	public static function getCanonical()
	{
		if ( self::$canonical === null )
		{
			/* Exception since AgoraException depends on Context. */
			return new Exception( 'No instance of Context.' ) ;
		}
		
		return self::$canonical ;
	}

	/** Set the only canonical.
	 * @param Context $context Canonical instance.
	 */
	public static function setCanonical( Context $context )
	{
		self::$canonical = $context ;
		
		return self::$canonical ;
	}

/******************************************************************************/

	/** Cast a parameter to a boolean.
	 * @param string $value String (or null) value to convert
	 */
	public static function booleanValue( $value )
	{
		return $value !== null ;
	}

	/** Cast a parameter to an array of strings.
	 * @param string $value String (or null) value to convert
	 */
	public static function arrayValue( $value )
	{
		return ( $value === null )
			? null
			: explode( ',', $value ) ;
	}

/******************************************************************************/

	/** Get the string value of a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more May be used by subclasses for selection precision (for example GET/POST), may be null.
	 * @return Value of the parameter or null.
	 */
	abstract public function getParameter( $key, $more = null ) ;

	/** Get the current user. */
	abstract public function getUser() ;

	/** Get the boolean value of a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more May be used by subclasses for selection precision (for example GET/POST), may be null.
	 */
	public function getBooleanParameter( $key, $more = null )
	{
		return self::booleanValue(
			$this->getParameter( $key, $more )
		) ;
	}

	/** Get the split value of a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more May be used by subclasses for selection precision (for example GET/POST), may be null.
	 */
	public function getArrayParameter( $key, $more = null )
	{
		return self::arrayValue(
			$this->getParameter( $key, $more )
		) ;
	}
	
	/** Get a the language. */
	public function getLanguage()
	{
		$param = $this->getParameter( 'language' ) ;
		
		return ( $param === null )
			? Configuration::getInstance()->getValue( 'user.defaultlang' )
			: $param ;
	}
	
	/** Get a translated message.
	 * @param string $key The name of the message.
	 * @param array $args The arguments.
	 */
	public function getMessage( $key, array $args  =array() )
	{
		return Languages::getInstance()->getMessage( $this->getLanguage(), $key, $args ) ;
	}
}
