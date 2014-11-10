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
	
	/** Answer the query. */
	abstract public function execute() ;
	
	/** Get a parameter of the query.
	 * @param string $key Unprefixed name of the parameter.
	 * @param integer $selector See WebContext::getParameter.
	 */
	protected function getParameter( $key, $selector )
	{
		return $this->context->getParameter( $this->prefix . '_' . $key, $selector ) ;
	}
	
}
