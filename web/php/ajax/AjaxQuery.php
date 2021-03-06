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
		return $this->context->getArrayParameter( 'query', WebContext::BOTH ) ;
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
	
	private function getExceptionResult( Exception $e )
	{
		$user = $e instanceof AgoraUserException ;
		
		$result = array(
			'success' => false,
			'type' => $user ? 'user' : 'internal'
		) ;
		
		$debug = Configuration::getValue( 'debug' ) ;
		
		if ( $user || $debug )
		{
			$result['error'] = get_class( $e ) ;
			$result['message'] = $e->getMessage() ;
			
			if ( $e instanceof AgoraException )
			{
				$result['struct'] = $e->getMessageStructure() ;
			}
		}
		
		if ( $debug )
		{
			$result['trace'] = $e->getTrace() ;
		}
		
		return $result ;
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
				if ( $data !== null )
				{
					$result['data'] = $data ;
				}
			}
			catch ( Exception $e )
			{
				$result = $this->getExceptionResult( $e ) ;
			}
			
			$this->result[$part] = $result ;
		}
	}
	
	/** Expose the result of the query to the client. */
	public function show()
	{
		header( 'Content-Type: application/json; charset=UTF-8' ) ;
		echo JSON::encode(
			$this->result,
			$this ->context->getBooleanParameter( 'indent' )
		) ;
	}
	
}
