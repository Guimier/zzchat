<?php
/** The context of execution.
 * Includes configuration (static context) and parameters (dynamic context).
 */
abstract class Context
{

	/** Configuration. */
	private $configuration ;

	/** Parameters. */
	private $parameters ;

	/** Constructor.
	 * @param Configuration $configuration The configuration.
	 * @param Parameters $parameters The parameters of the call.
	 */
	protected function __construct(
		Configuration $configuration,
		Parameters $parameters
	)
	{
		$this->configuration = $configuration ;
		$this->parameters = $parameters ;
	}

	/** Get the Parameters object. */
	public function getParameters()
	{
		return $this->parameters ;
	}

	/** Get the Configuration object. */
	public function getConfiguration()
	{
		return $this->configuration ;
	}

}
