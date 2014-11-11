<?php
/** The parameters of the call. */
abstract class Parameters
{

	/** Cast a parameter to a boolean.
	 * @param string $value String (or null) value to convert
	 */
	static function booleanValue( $value )
	{
		return $value !== null ;
	}

	/** Cast a parameter to an array of strings.
	 * @param string $value String (or null) value to convert
	 */
	static function arrayValue( $value )
	{
		return ( $value === null )
			? null
			: explode( ',', $value ) ;
	}

	/** Get the string value of a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more May be used by subclasses for selection precision (for example GET/POST), may be null.
	 * @return Value of the parameter or null.
	 */
	abstract public function getValue( $key, $more = null ) ;

	/** Get the boolean value of a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more May be used by subclasses for selection precision (for example GET/POST), may be null.
	 */
	public function getBooleanValue( $key, $more = null )
	{
		return self::booleanValue(
			$this->getValue( $key, $more )
		) ;
	}

	/** Get the split value of a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more May be used by subclasses for selection precision (for example GET/POST), may be null.
	 */
	public function getValues( $key, $more = null )
	{
		return self::arrayValue(
			$this->getValue( $key, $more )
		) ;
	}

}
