<?php

/** An Ajax query. */
class AjaxQuery
{
	
	/** The current Context. */
	private $context ;
	
	/** The results. */
	private $result = array() ;
	
	/** Constructor.
	 * @param WebContext $context The context in which the query is answered.
	 */
	public function __construct( WebContext $context )
	{
		$this->context = $context ;
	}
	
	/** Extract the names of the modules to execute.
	 * @return Ordered array of the modules.
	 */
	private function extractParts()
	{
		$raw = $this
			->context
			->getParameters()
			->getValue( 'query', WebParameters::BOTH ) ;

		return ( $raw == null ) ? array() : explode( ',', $raw ) ;
	}
	
	/** Return the name of the class associated to a part.
	 * @param string $part The name of the part.
	 * @return The name of the class.
	 */
	private function className( $part )
	{
		return ucfirst( $part ) . 'AjaxQueryPart' ;
	}
	
	/** Execute a part of the query.
	 * @param string $part Name of the part.
	 * @throws NoSuchQueryPartException If no corresponding class exists.
	 * @return Data returned by the query part.
	 */
	private function executePart( $part )
	{
		$class = $this->className( $part ) ;
		if ( ! class_exists( $class, true ) )
		{
			throw new NoSuchQueryPartException( $part ) ;
		}
		
		$queryPart = new $class( $part, $this->context ) ;
		return $queryPart->execute() ;
	}
	
	/** Execute all parts of the query. */
	public function execute()
	{
		$parts = $this->extractParts() ;
		
		foreach ( $parts as $part )
		{
			try
			{
				$data  = $this->executePart( $part ) ;
				$result = array( 'success' => true ) ;
				if ( $data != null )
				{
					$result['data'] = $data ;
				}
			}
			catch ( Exception $e )
			{
				$result = array(
					'success' => false,
					'error' => get_class( $e )
				) ;
			}
			$this->result[$part] = $result ;
		}
	}
	
	/** Get JSON options. */
	private function jsonOptions()
	{
		$opts = 0 ;
		
		if ( defined( 'JSON_UNESCAPED_UNICODE' ) )
		{
			$opts |= JSON_UNESCAPED_UNICODE ;
		}

		$indent = $this
			->context
			->getParameters()
			->getBooleanValue( 'indent' ) ;

		if ( $indent && defined( 'JSON_PRETTY_PRINT' ) )
		{
			$opts |= JSON_PRETTY_PRINT ;
		}
		
		return $opts ;
	}
	
	/** Expose te result of the query to the client.
	 * @codeCoverageIgnore
	 */
	public function show()
	{
		header( 'Content-Type: application/json; charset=UTF-8' ) ;
		echo json_encode( $this->result, $this->jsonOptions() ) ;
	}
	
}
