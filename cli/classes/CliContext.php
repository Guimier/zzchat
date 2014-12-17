<?php

/** Command line parameters. */
class CliContext extends Context
{
	
	/** Array of detected parameters. */
	private $params = array() ;
	
	/** Number of unnamed parameters. */
	private $unnamedCount = 0 ;
	
	/** Constructor.
	 * @param array $argv Array of command line arguments.
	 */
	public function __construct( $argv )
	{
		parent::__construct() ;
		
		$this->parseArguments( $argv ) ;
	}
	
	/** Turn command line arguments into an associative array of strings.
	 * @param array $argv Array of command line arguments.
	 */
	private function parseArguments( $argv )
	{
		/* Should all parameters be considered as unnamed? */
		$forceUnnamed = false ;
		
		for ( $i = 1 ; $i < count( $argv ) ; $i++ )
		{
			/* Current argument. */
			$arg = $argv[$i] ;
			
			if ( $forceUnnamed )
			{
				$this->addUnnamedParameter( $arg ) ;
			}
			else if ( $arg == '--' )
			{
				/* All arguments following -- are unnamed ones. */
				$forceUnnamed = true ;
			}
			else if ( preg_match( '/^-\w$/', $arg ) )
			{
				/* Named non-empty parameter. */
				$chars = substr( $arg, 1 ) ;
				foreach ( str_split( $chars ) as $char )
				{
					$this->addNamedParameter( $char, '' ) ;
				}
			}
			else if ( preg_match( '/^--[\w-]+=.+$/', $arg ) )
			{
				/* Named non-empty parameter. */
				$arg = substr( $arg, 2 ) ;
				$pos = strpos( $arg, '=' ) ;
				$this->addNamedParameter(
					substr( $arg, 0, $pos ),
					substr( $arg, $pos + 1 )
				) ;
				
			}
			else if ( preg_match( '/--[\w-]/', $arg ) )
			{
				$this->addNamedParameter( substr( $arg, 2 ), '' ) ;
			}
			else
			{
				$this->addUnnamedParameter( $arg ) ;
			}
		}
	}
	
	/** Add an unnamed parameter to the detected parameters.
	 * @param string $value Value of the parameter.
	 */
	private function addUnnamedParameter( $value )
	{
		$this->params[$this->unnamedCount++] = $value ;
	}
	
	/** Add an named parameter to the detected parameters.
	 * @param string $name Name of the parameter.
	 * @param string $value Value of the parameter.
	 * @todo Do we want to warn/throw on double definition?
	 */
	private function addNamedParameter( $name, $value )
	{
		$this->params[$name] = $value ;
	}
	
	/** Get a parameter.
	 * @param string $key Name of the parameter.
	 * @param null $more May be used in the future, required by parent class Context.
	 */
	function getParameter( $key, $more = null )
	{
		$value = null ;
		
		if ( array_key_exists( $key, $this->params ) )
		{
			$value = $this->params[$key] ;
		}
		
		return $value ;
	}

	/** Get a boolean parameter.
	 * @param string $key Name of the parameter.
	 * @param string $more Shortcut (for example 'h' for '-h')
	 */
	public function getBooleanParameter( $key, $more = null )
	{
		return ( $more !== null && array_key_exists( $more, $this->params ) ) ||
			parent::getBooleanParameter( $key, null ) ;
	}
	
	/** Get the current user.
	 * Nobody just now, but could be a special user in the future.
	 */
	public function getUser()
	{
		return User::getById( -1 ) ;
	}
	
}
