<?php

/** Context of execution in case of a web query.
 * The dynamic parameters in this type of context are the given as GET or POST parameters.
 */
class WebContext extends Context
{
	
	const POST = 1 ; ///< Parameter selection in #getParameter: only POST
	const GET  = 2 ; ///< Parameter selection in #getParameter: only GET
	const BOTH = 3 ; ///< Parameter selection in #getParameter: POST or GET
	
	/** Get a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more Which parameter to get, one of #POST, #GET and #BOTH.
	 *   In case BOTH, POST parameter takes priority over GET one.
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
