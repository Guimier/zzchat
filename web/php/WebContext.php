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
	/* Current User. */
	private $user = null ;
	
	/** Constructor.
	 * @param array $getParams Array of GET parameters ($_GET).
	 * @param array $postParams Array of POST parameters ($_POST).
	 */
	public function __construct( $getParams, $postParams )
	{
		$this->getParams = $getParams ;
		$this->postParams = $postParams ;
		
		session_start() ;
		if ( array_key_exists( 'user-id', $_SESSION ) )
		{
			$user = getUser( $_SESSION['user-id'] ) ;
			
			if ( $user->isActive() ) 
			{
				$this->user = $user ;
			}
		}
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
	
	
	/**	Create and connect an user with the name in parameter.
	 * 
	 * @param string $userName The name of the user.
	 * 
	 */ 
	function connect( $userName )
	{
		$this->user = Users::createUser( $userName ) ;	
	}
	
	/**	Disconnect an user with the name in parameter.
	 * 
	 * @throw NotLoggedInUserException If no user is associated to the session.
	 */ 
	function disconnect()
	{
		if ( $user === null )
		{
			throw new NotLoggedInUserException() ;
		}
		else
		{
			$this->user->isNowInactive() ;
			$this->user = null ;
		}
	}
	/** Destructor */ 
	public function __destruct()
	{
		if ( $this->user === null )
		{
			unset ( $_SESSION['user-id'] ) ;
		}
		else
		{
			$_SESSION['user-id'] = $this->user->getId() ;
		}
	}
	
}

