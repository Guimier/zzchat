<?php

/** JSON management. */
class JSON
{

	/** Get a JSON string representation of a value.
	 * @param mixed $data The value to encode.
	 * @param number $jsonOpts Options passed to json_encode’s second parameter.
	 */
	public function encode( $data, $jsonOpts = 0 )
	{
		$opts = $jsonOpts ;
		
		if ( defined( 'JSON_UNESCAPED_UNICODE' ) )
		{
			$opts |= JSON_UNESCAPED_UNICODE ;
		}
		
		return json_encode( $data, $opts ) ;
	}

	/** Get the value represented by a JSON string (with arrays).
	 * @param string $string The JSON string.
	 */
	public function decode( $string )
	{
		return json_decode( $string, true ) ;
	}

}
