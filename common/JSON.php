<?php

/** JSON management. */
class JSON
{

	/** Get a JSON string representation of a value.
	 * @param mixed $data The value to encode.
	 * @param boolean [$indent] Whether or not to indent.
	 */
	public static function encode( $data, $indent = false )
	{
		$opts = 0 ;
		
		if ( defined( 'JSON_UNESCAPED_UNICODE' ) )
		{
			$opts |= JSON_UNESCAPED_UNICODE ;
		}
		
		if ( $indent && defined( 'JSON_PRETTY_PRINT' ) )
		{
			$opts = JSON_PRETTY_PRINT ;
		}
		
		return json_encode( $data, $opts ) ;
	}

	/** Get the value represented by a JSON string (with arrays).
	 * @param string $string The JSON string.
	 */
	public static function decode( $string )
	{
		return json_decode( $string, true ) ;
	}

	/** Get the JSON value if valid, the raw string instead.
	 * @param string $string The (maybe) JSON string to decode.
	 */
	public static function decodeOrRaw( $string )
	{
		$res = self::decode( $string ) ;

		if ( is_null( $res ) )
		{
			$res = $string ;
		}

		return $res ;
	}

}
