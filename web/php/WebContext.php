<?php

/** The context of the web query. */
class WebContext extends Context
{
	
	/** Parameter selection in #getParameter: search in POST data. */
	const GET = 1 ;
	/** Parameter selection in #getParameter: search in GET data. */
	const POST = 2 ;
	/** Parameter selection in #getParameter: search in both POST or GET data. */
	const BOTH = 3 ;
	
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
	 * @param int [$more] Which parameter to get, one of #POST, #GET and #BOTH.
	 *   In case BOTH, POST parameter takes priority over GET one.
	 * @throw BadCallException Thrown if $more is not valid
	 */
	function getParameter( $key, $more = null )
	{
		if ( $more === null )
		{
			$more = self::BOTH ;
		}

		$value = null ;

		if (
			$more & self::POST &&
			array_key_exists( $key, $this->postParams )
		)
		{
			$value = $this->postParams[$key] ;
		} else if (
			$more & self::GET &&
			array_key_exists( $key, $this->getParams )
		)
		{
			$value = $this->getParams[$key] ;
		}

		return $value ;
	}
	
}

