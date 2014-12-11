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
			self::errln( $e->getMessage() ) ;
			if ( Configuration::getValue( 'debug' ) )
			{
				echo JSON::encode( $e->getTrace(), true ) ;
			}
			exit ( 1 ) ;
		}
	}

	/** Constructor. */
	private function __construct( $commandName )
	{
		$this->commandName = $commandName ;
	}

	/** Executable part of the command line.
	 * Override if you don’t want auto-splitting.
	 */
	protected function execute()
	{
		$method = $this->getMethod() ;
		$this->$method() ;
	}

	/** Get formated documentation.
	 * @return Associative array describing the command line.
	 *    * Value at `description` (message name) is used as script global description.
	 *    * `scenarios` may contain a list of scenarios (array keyed by scenarios names with arrays as values):
	 *      * `description`: Name of a message describing the scenario.
	 *      * `parameters`: Ordered array of used parameters (names which may be prefixed by `+` if the parameter is required).
	 *    * `parameters` may contain a list of parameters (array keyed by arguments names with arrays as values):
	 *      * `description`: Name of a translated description
	 *      * `type`: one of `boolean`, `string`, `array`
	 *      * `alt`: only for boolean, one-char string for an alternative flag.
	 */
	abstract public function getDocumentation() ;

	/** Say the action was not found and display the documentation. */
	private function noSuchAction()
	{
		$this->writeln( $this->getContext()->getMessage( 'cli.help.nosuchaction' ) ) ;
		$this->writeln( '--------------------' ) ;
		$this->showDocumentation() ;
	}

	/** Check whether a parameter is required, according to the documentation.
	 * @param string $parameter Parameter call in documentation.
	 */
	private function isRequiredParameter( $parameter )
	{
		return $parameter[0] === '+' ;
	}

	/** Get the parameter name from the documentation format.
	 * @param string $parameter Parameter call in documentation.
	 */
	private function getParameterName( $parameter )
	{
		return $parameter[0] === '+'
			? substr( $parameter, 1 )
			: $parameter ;
	}

	/** Check all required parameters are present.
	 * @param array $parameters List of expected parameters.
	 */
	private function checkParameters( $parameters )
	{
		foreach ( $parameters as $param )
		{
			$name = $this->getParameterName( $param ) ;
			if (
				$this->isRequiredParameter( $param )
				&& $this->getContext()->getParameter( $name ) === null
			)
			{
				throw new CliMissingParameterException( $name ) ;
			}
		}
	}

	/** Get the name of the method to call (auto-splitted commands).
	 * Will check wether the required parameters are present.
	 */
	private function getMethod()
	{
		$firstParam = $this->getContext()->getParameter( 0 ) ;
		$doc = $this->getDocumentation() ;
		$method = 'noSuchAction' ;
		
		if (
			$firstParam !== null
			&& array_key_exists( 'scenarios', $doc )
			&& array_key_exists( $firstParam, $doc['scenarios'] )
		)
		{
			$method = "execute_$firstParam" ;
			$this->checkParameters(
				$doc['scenarios'][$firstParam]['parameters']
			) ;
		}
		
		return $method ;
	}

	/** Show a list of parameters for a script.
	 * @param string $name Name of the parameter.
	 * @param array $param Associative array representing a parameter.
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
		$this->writeln() ;
		$this->writeln( strtoupper(
			$this->getContext()->getMessage( $title )
		) );
	}

	/** Show usage scenarios.
	 * @param array $scenarios Scenarios list.
	 * @param array $params Parameters list.
	 */
	private function showScenarios( $scenarios, $params )
	{
		$command = 'cli/' . $this->commandName ;
		
		foreach ( $scenarios as $key => $desc )
		{
			$parts = array( $command, $key ) ;

			foreach ( $desc['parameters'] as $param )
			{
				$part = '--' . $this->getParameterName( $param ) ;
				if ( ! $this->isRequiredParameter( $param ) )
				{
					$part = "[$part]" ;
				}
				$parts[] = $part ;
			}

			$this->writeln( "\t" . implode( ' ', $parts ) ) ;
			$this->writeln( "\t\t" . $this->getContext()->getMessage(
				$desc['description']
			) ) ;
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
			$this->showScenarios( $desc['scenarios'], $params ) ;
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
	protected static function writeln( $message = '' )
	{
		echo "$message\n" ;
	}

	/** Print a line on stderr.
	 * @param string $message Error or warning message.
	 */
	protected static function errln( $message )
	{
		fwrite( STDERR, "$message\n" ) ;
	}

	/** Get a parameter (type from documentation).
	 * @param string $name Parameter name.
	 */
	protected function getParameter( $name )
	{
		$doc = $this->getDocumentation() ;
		$desc = $doc['parameters'][$name] ;
		$context = $this->getContext() ;
		$res = null ;
		
		switch ( $desc['type'] )
		{
			case 'string':
				$res = $context->getParameter( $name ) ;
				break ;
			case 'array':
				$res = $context->getArrayParameter( $name ) ;
				break ;
			case 'boolean':
				$res = array_key_exists( 'alt', $desc )
					? $context->getBooleanParameter( $name, $desc['alt'] )
					: $context->getBooleanParameter( $name ) ; ;
				break ;
		}
		
		return $res ;
	}

}

