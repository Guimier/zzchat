<?php

class WebContext extends Context
{
	
	const POST = 1 ;
	const GET  = 2 ;
	const BOTH = 3 ;
	
	/*
	 * Get a parameter of the call.
	 * $key is the name of the parameter.
	 * $more may be one of POST, GET, BOTH.
	 *       In case BOTH, POST takes priority over GET.
	 */
	function getParameter( $key, $more )
	{
		$value = null ;
		
		switch ( $more )
		{
			case self::POST :
				if ( array_key_exists( $key, $_POST ) )
				{
					$value = $_POST[$key] ;
				}
				break ;
			
			case self::GET :
				if ( array_key_exists( $key, $_GET ) )
				{
					$value = $_GET[$key] ;
				}
				break ;
			
			case self::BOTH :
				if ( array_key_exists( $key, $_POST ) )
				{
					$value = $_POST[$key] ;
				}
				else if ( array_key_exists( $key, $_GET ) )
				{
					$value = $_GET[$key] ;
				}
				break ;
			
			default:
				throw new BadCallException( __METHOD__ ) ;
		}
		
		return $value ;
	}
	
}
