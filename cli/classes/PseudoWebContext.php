<?php

/** Pseudo WebContext used for CLI ajax requests. */
class PseudoWebContext extends WebContext
{
	
	/** Real context. */
	private $trueContext ;
	
	/** Constructor.
	 * @param array $getParams Array of GET parameters ($_GET).
	 * @param array $postParams Array of POST parameters ($_POST).
	 */
	public function __construct( CliContext $trueContext )
	{
		$this->trueContext = $trueContext ;
	}
	
	/** Get a parameter.
	 * @param string $key Name of the parameter.
	 * @param null [$more]
	 */
	function getParameter( $key, $more = null )
	{
		return $this->trueContext->getParameter( $key ) ;
	}
	
	/** Get the current user.
	 * @codeCoverageIgnore Getter.
	 */
	public function getUser()
	{
		return null ;
	}
	
	/** Create and connect an user with the name in parameter.
	 * 
	 * @param string $userName The name of the user.
	 * 
	 */ 
	public function connect( $userName )
	{
		$this->user = User::createUser( $userName ) ;
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
			unset ( $_SESSION['user-id'] ) ;
		}
		else
		{
			$_SESSION['user-id'] = $this->user->getId() ;
		}
	}
	
}

