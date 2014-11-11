<?php

/** The parameters of the web query. */
class WebParameters extends Parameters
{
	
	/** Parameter selection in #getParameter: only POST */
	const POST = 1 ;
	/** Parameter selection in #getParameter: POST or GET */
	const BOTH = 2 ;
	
	/** Array of GET parameters ($_GET). */
	private $getParams ;
	/** Array of POST parameters ($_POST). */
	private $postParams ;
	
	/** Constructor.
	 * @param array $getParams Array of GET parameters ($_GET).
	 * @param array $postParams Array of POST parameters ($_POST).
	 */
	public function __construct( $getParams, $postParams )
	{
		$this->getParams = $getParams ;
		$this->postParams = $postParams ;
	}
	
	/** Get a parameter.
	 * @param string $key Name of the parameter.
	 * @param [$more] Which parameter to get, one of #POST, #GET and #BOTH.
	 *   In case BOTH, POST parameter takes priority over GET one.
	 * @throw BadCallException Thrown if $more is not valid
	 */
	function getValue( $key, $more = null )
	{
		if ( $more === null )
		{
			$more = self::BOTH ;
		}

		$value = null ;
		
		switch ( $more )
		{
			case self::BOTH :
				if ( array_key_exists( $key, $this->getParams ) )
				{
					$value = $this->getParams[$key] ;
				}
				// No break: POST may override.

			case self::POST :
				if ( array_key_exists( $key, $this->postParams ) )
				{
					$value = $this->postParams[$key] ;
				}
			
				break ;
			
			// @codeCoverageIgnoreStart
			default:
				throw new BadCallException( __METHOD__ ) ;
			// @codeCoverageIgnoreStop
		}
		
		return $value ;
	}
	
}

