<?php

/** Context of execution in case of a web query.
 * The dynamic parameters in this type of context are the given as GET or POST parameters.
 */
class WebContext extends Context
{
	
	const POST = 1 ; ///< Parameter selection in #getParameter: only POST
	const GET  = 2 ; ///< Parameter selection in #getParameter: only GET
	const BOTH = 3 ; ///< Parameter selection in #getParameter: POST or GET
	
	/** Array of GET parameters ($_GET). */
	private $getParams ;
	/** Array of POST parameters ($_POST). */
	private $postParams ;
	
	/** Constructor.
	 * @param string $root Full path to the root directory of Agora.
	 * @param array $getParams Array of GET parameters ($_GET).
	 * @param array $postParams Array of POST parameters ($_POST).
	 */
	public function __construct( $root, $getParams, $postParams )
	{
		parent::__construct( $root ) ;
		$this->getParams = $getParams ;
		$this->postParams = $postParams ;
	}
	
	/** Get a parameter.
	 * @param string $key Name of the parameter.
	 * @param $more Which parameter to get, one of #POST, #GET and #BOTH.
	 *   In case BOTH, POST parameter takes priority over GET one.
	 * @throw BadCallException Thrown if $more is not valid
	 */
	function getParameter( $key, $more )
	{
		$value = null ;
		
		switch ( $more )
		{
			case self::POST :
				if ( array_key_exists( $key, $this->postParams ) )
				{
					$value = $this->postParams[$key] ;
				}
				break ;
			
			case self::GET :
				if ( array_key_exists( $key, $this->getParams ) )
				{
					$value = $this->getParams[$key] ;
				}
				break ;
			
			case self::BOTH :
				if ( array_key_exists( $key, $this->postParams ) )
				{
					$value = $this->postParams[$key] ;
				}
				else if ( array_key_exists( $key, $this->getParams ) )
				{
					$value = $this->getParams[$key] ;
				}
				break ;
			
			default:
				throw new BadCallException( __METHOD__ ) ;
		}
		
		return $value ;
	}
	
}
