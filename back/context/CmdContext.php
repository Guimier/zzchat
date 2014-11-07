<?php

/** Context of execution in case of a command line call. */
class CmdContext extends Context
{
	
	/** Array of detected parameters. */
	private $params = array() ;
	
	/** Number of unnamed parameters. */
	private $unnamedCount = 0 ;
	
	/** Constructor.
	 * @param string $root Full path to the root directory of Agora.
	 * @param array $argv Array of command line arguments.
	 */
	public function __construct( $root, $argv )
	{
		parent::__construct( $root ) ;
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
			else if ( preg_match( '/--[\w-]+=.+/', $arg ) )
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
	 * @todo Do we want to warn/throw on double execution?
	 */
	private function addNamedParameter( $name, $value )
	{
		$this->params[$name] = $value ;
	}
	
	/** Get a parameter.
	 * @param string $key Name of the parameter.
	 * @param null $more May be used in the future, required by parent class Context.
	 */
	function getParameter( $key, $more )
	{
		$value = null ;
		/*
		$f = fopen( '/dev/tty', 'w' ) ;
		fputs( $f, json_encode( $this->params ) ) ;
		fputs( $f, "\n" ) ;
		fclose( $f ) ;
		*/
		if ( array_key_exists( $key, $this->params ) )
		{
			$value = $this->params[$key] ;
		}
		
		return $value ;
	}
	
}
