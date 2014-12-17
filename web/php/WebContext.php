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
	/** Array of session variables ($_SESSION). */
	private $session ;
	/* Current User. */
	private $user = null ;
	
	/** Get the session key for th user-id. */
	public function getSessionKey()
	{
		$prefix = Configuration::getValue( 'prefix' ) ;
		return $prefix === '' ? 'user-id' : "$prefix-user-id" ;
	}
	
	/** Constructor.
	 * @param array $getParams Array of GET parameters ($_GET).
	 * @param array $postParams Array of POST parameters ($_POST).
	 * @param array $postParams Array of sessions variables ($_SESSION).
	 */
	public function __construct( $getParams, $postParams, &$session )
	{
		parent::__construct() ;
		
		$this->getParams = $getParams ;
		$this->postParams = $postParams ;
		$this->session = &$session ;
		
		if ( array_key_exists( $this->getSessionKey(), $this->session ) )
		{
			try
			{
				$user = User::getById( $this->session[$this->getSessionKey()] ) ;

				if( $user->isActive() )
				{
					$this->user = $user ;
					$user->isActiveNow() ;
				}
			}
			catch ( NoSuchEntityException $e ) {}
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
	
	/** Get the current user.
	 * @codeCoverageIgnore Getter.
	 */
	public function getUser()
	{
		return $this->user ;
	}
	
	/**	Create and connect an user with the name in parameter.
	 * 
	 * @param string $userName The name of the user.
	 * 
	 */ 
	public function connect( $userName )
	{
		$this->user = User::create( $userName ) ;
	}
	
	/**	Disconnect an user with the name in parameter.
	 * 
	 * @throw NotLoggedInUserException If no user is associated to the session.
	 */ 
	public function disconnect()
	{
		if ( $this->user === null )
		{
			throw new NotLoggedInUserException() ;
		}
		else
		{
			$this->user->isNowInactive() ;
			$this->user = null ;
		}
	}
	
	/** Destructor. */
	public function __destruct()
	{
		if ( $this->user === null )
		{
			unset ( $this->session[$this->getSessionKey()] ) ;
		}
		else
		{
			$this->session[$this->getSessionKey()] = $this->user->getId() ;
		}
	}
	
}
