<?php

/** Context of execution in case of a command line call.
 * @warning Not unit-tested (passes globals to another class).
 * @codeCoverageIgnore
 */
class CliContext extends Context
{
	/** Constructor.
	 * @param Configuration $configuration The configuration.
	 */
	public function __construct( Configuration $configuration )
	{
		global $argv ;

		parent::__construct(
			$configuration,
			new CliParameters( $argv )
		) ;
	}
	
	
}
