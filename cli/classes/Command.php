<?php

/** Command line script. */ 
abstract class Command
{
	
	/* Name of the command. */
	private $commandName ;
	
	/** Run the command line.
	 * @param string $className Name of the managing class.
	 */
	public static function run( $commandName )
	{
		$className = ucfirst( $commandName ) . 'Command' ;
		
		try
		{
			$command = new $className( $commandName ) ;
			if ( Context::getCanonical()->getBooleanParameter( 'help', 'h' ) )
			{
				$command->showDocumentation() ;
			}
			else
			{
				$command->execute() ;
			}
			exit ( 0 ) ;
		}
		catch ( Exception $e )
		{
			echo $e->getMessage() ;
			exit ( 1 ) ;
		}
	}

	/** Constructor. */
	private function __construct( $commandName )
	{
		$this->commandName = $commandName ;
	}

	/** Executable part of the command line. */
	abstract protected function execute() ;

	/** Get formated documentation.
	 * @return Associative array describing the commandline.
	 *   * Keys `required` and `optional` may contain a list of arguments (see showDocumentationPart for the format).
	 *   * Key `desc` must contain the name of a translated description for the commandline.
	 */
	abstract public function getDocumentation() ;

	/** Show a list of parameters for a script.
	 * @param string $name Name of the parameter.
	 * @param array $param Associative array representing a parameter.
	 *   Keys are the names, values are arrays containing:
	 *   * `desc`: Name of a translated description
	 *   * `type`: one of `boolean`, `string`, `array`
	 *   * `alt`: only for boolean, one-char string for an alternative flag.
	 */
	private function showParameterDocumentation( $name, $param )
	{
		$format = "--$name" ;

		switch ( $param['type'] )
		{
			case 'boolean' :
				if ( array_key_exists( 'alt', $param ) )
				{
					$format = '-' . $param['alt'] . ", $format" ;
				}
				break ;
			case 'string' :
				$format .= '=<string>' ;
				break ;
			case 'array' :
				$format .= '=<string>[,…]' ;
				break ;
			default :
				$format .= '=???' ;
		}

		$this->writeln( "\t$format" ) ;
		$this->writeln( "\t\t" . $this->getContext()->getMessage( $param['description'] ) ) ;
	}

	/** Show a list of parameters for a script.
	 * @param array $params Associative array representing of the parameters descriptions.
	 */
	private function showParametersDocumentation( $params )
	{
		ksort( $params ) ;
		foreach ( $params as $name => $param )
		{
			$this->showParameterDocumentation( $name, $param ) ;
		}
	}

	/** Show a title for the documentation.
	 * @param string $title Name of the message used for the title.
	 */
	private function showDocumentationTitle( $title )
	{
		echo "\n" . strtoupper(
			$this->getContext()->getMessage( $title )
		) . "\n" ;
	}

	/**
	 *
	 */
	private function showScenarios( $scenarios, $params )
	{
		$command = 'cli/' . $this->commandName . '.php' ;
		
		foreach ( $scenarios as $key => $desc )
		{
			echo "\t$command $key" ;
			foreach ( $desc['parameters'] as $param )
			{
				if ( $params[$param]['required'] )
				{
					echo " --$param" ;
				}
				else
				{
					echo " [--$param]" ;
				}
			}
			echo "\n" ;
		}
	}

	/** Show the documentation. */
	protected function showDocumentation()
	{
		$desc = $this->getDocumentation() ;
		$this->writeln(
			$this->getContext()->getMessage( $desc['description'] )
		) ;

		$params = array_key_exists( 'parameters', $desc )
			? $desc['parameters']
			: array() ;

		if ( array_key_exists( 'scenarios', $desc ) )
		{
			$this->showDocumentationTitle( 'cli.help.scenarios' ) ;
			$this->showScenarios( $desc['scenarios'], $params) ;
		}

		if ( count( $params ) )
		{
			$this->showDocumentationTitle( 'cli.help.parameters' ) ;
			$this->showParametersDocumentation( $params ) ;
		}

		$this->showDocumentationTitle( 'cli.help.common' ) ;
		$this->showParametersDocumentation( array(
			'help' => array(
				'type' => 'boolean',
				'description' => 'cli.help.common.help',
				'alt' => 'h'
			),
			'language' => array(
				'type' => 'string',
				'description' => 'cli.help.common.language'
			)
		) ) ;
	}

	/** Get the context of execution. */
	protected function getContext()
	{
		// Until we need something more sophisticated…
		return Context::getCanonical() ;
	}

	/** Print a line on the console.
	 * @param string [$message=''] Message to print.
	 */
	protected function writeln( $message = '' )
	{
		echo "$message\n" ;
	}

	/** Print a line on stderr.
	 * @param string $message Error or warning message.
	 */
	protected function errln( $message )
	{
		fwrite( STDERR, "$message\n" ) ;
	}

}
