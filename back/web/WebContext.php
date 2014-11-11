<?php

/** Context of execution in case of a web query.
 * The dynamic parameters in this type of context are the given as GET or POST parameters.
 * @warning Not unit-tested (passes globals to another class).
 * @codeCoverageIgnore
 */
class WebContext extends Context
{
	
	/** Constructor.
	 * @param Configuration $configuration The configuration.
	 */
	public function __construct( Configuration $configuration )
	{
		parent::__construct(
			$configuration,
			new WebParameters( $_GET, $_POST )
		) ;
	}
	
}
