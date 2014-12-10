<?php

abstract class AjaxQueryPart
{
	
	/** Prefix of parameters. */
	private $prefix ;
	
	/** The current Context. */
	private $context ;
	
	/** Constructor.
	 * @param string $prefix Prefix of parameters.
	 * @param WebContext $context Current Context.
	 */
	final public function __construct( $prefix, WebContext $context )
	{
		$this->prefix = $prefix ;
		$this->context = $context ;
	}
	
	/** Get the context. */
	protected function getContext()
	{
		return $this->context ;
	}
	
	/** Answer the query. */
	abstract public function execute() ;
	
	/** Get a parameter of the query.
	 * @param string $key Unprefixed name of the parameter.
	 * @param integer $selector See WebContext::getParameter.
	 */
	protected function getParameter( $key, $selector = null )
	{
		return $this->context->getParameter( $this->prefix . '_' . $key, $selector ) ;
	}
	
	/** Get a boolean parameter of the query.
	 * @param string $key Unprefixed name of the parameter.
	 * @param integer $selector See WebContext::getParameter.
	 */
	protected function getBooleanParameter( $key, $selector = null )
	{
		return $this->context->getBooleanParameter( $this->prefix . '_' . $key, $selector ) ;
	}
	
	/** Get an array parameter of the query.
	 * @param string $key Unprefixed name of the parameter.
	 * @param integer $selector See WebContext::getParameter.
	 */
	protected function getArrayParameter( $key, $selector = null )
	{
		return $this->context->getArrayParameter( $this->prefix . '_' . $key, $selector ) ;
	}
	
	/** This request may be executed only if logged in.
	 * @return the current user.
	 * @throws NotLoggedInUserException If the user is not logged in.
	 */
	protected function loggedInOnly()
	{
		$user = Context::getCanonical()->getUser() ;
		
		if ( $user === null )
		{
			throw new NotLoggedInUserException() ;
		}
		
		return $user ;
	}
	
}
