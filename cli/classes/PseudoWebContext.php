<?php

/** Pseudo WebContext used for CLI ajax requests. */
class PseudoWebContext extends WebContext
{
	
	/** Real context. */
	private $trueContext ;
	
	/** Constructor.
	 * @param CliContext $trueContext Real context.
	 */
	public function __construct( Context $trueContext )
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
		return $this->trueContext->getUser() ;
	}
	
	/** Get the time.
	 * @codeCoverageIgnore Getter.
	 */
	public function getTime()
	{
		return $this->trueContext->getTime() ;
	}
	
	/** Create and connect an user with the name in parameter.
	 * 
	 * @param string $userName The name of the user.
	 */ 
	public function connect( $userName ) {}
	
	/**	Disconnect an user with the name in parameter. */ 
	public function disconnect() {}
	
	/** Destructor. */
	public function __destruct() {}
	
}

