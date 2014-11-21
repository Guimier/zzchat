<?php

/** Command line script. */ 
abstract class Command
{
	
	/** Run the command line.
	 * @param string $className Name of the managing class.
	 */
	public static function run( $className )
	{
		try
		{
			$command = new $className() ;
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

	/** Executable part of the command line. */
	abstract protected function execute() ;

	/** Get formated documentation.
	 * @return Associative array describing the commandline.
	 *   * Keys `required` and `optional` may contain a list of arguments (see showDocumentationPart for the format).
	 *   * Key `desc` must contain the name of a translated description for the commandline.
	 */
	abstract public function getDocumentation() ;

	/** Show a list of parameters for a script.
	 * @param string $title Name of a translated name for the description.
	 * @param array $parameters Associative array of parameters.
	 *   Keys are the names, values are arrays containing:
	 *   * `desc`: Name of a translated description
	 *   * `type`: one of `boolean`, `string`, `array`
	 *   * `alt`: only for boolean, one-char string for an alternative flag.
	 */
	private function showDocumentationPart( $title, array $parameters )
	{
			ksort( $parameters ) ;

			$this->writeln() ;
			$this->writeln( $this->getContext()->getMessage( $title ) ) ;

		foreach ( $parameters as $key => $desc )
		{
			$format = "--$key" ;

			switch ( $desc['type'] )
			{
				case 'boolean' :
					if ( array_key_exists( 'alt', $desc ) )
					{
						$format = '-' . $desc['alt'] . ", $format" ;
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
			$this->writeln( "\t\t" . $this->getContext()->getMessage( $desc['desc'] ) ) ;
		}
	}

	/** Show the documentation. */
	protected function showDocumentation()
	{
		$context = $this->getContext() ;
		$desc = $this->getDocumentation() ;

		$this->writeln( $context->getMessage( 'cli.help' ) ) ;
		$this->writeln() ;
		$this->writeln( $context->getMessage( $desc['desc'] ) ) ;

		if ( array_key_exists( 'required', $desc ) )
		{
			$this->showDocumentationPart(
				'cli.help.required',
				$desc['required']
			) ;
		}

		if ( array_key_exists( 'optional', $desc ) )
		{
			$this->showDocumentationPart(
				'cli.help.optional',
				$desc['optional']
			) ;
		}

		$this->showDocumentationPart(
			'cli.help.common',
			array(
				'help' => array(
					'type' => 'boolean',
					'desc' => 'cli.help.common.help',
					'alt' => 'h'
				),
				'language' => array(
					'type' => 'string',
					'desc' => 'cli.help.common.language'
				)
			)
		) ;
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
