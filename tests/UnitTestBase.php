<?php
/*
 * Common initialisation for PHPUnit tests.
 */

class UnitTestBase
{
	
	private $className ;

	/*
	 * Constructor.
	 */
	public function __construct(
		/* Name of the class that will be tested */
		$className,
		/* Directory where the class is located (relative to zzchat root directory) */
		$relativeDir
	)
	{
		require_once $this->getRootDir() . '/' . $relativeDir . '/' . $className . '.php' ;
		$this->className = $className ;
	}

	/*
	 * Get full path to root directory of zzchat.
	 */
	public function getRootDir()
	{
		return dirname( __DIR__ ) ;
	}

	/*
	 * Get relative data path.
	 */
	public function getDataPath( $fileName )
	{
		return 'tests/data/' . $this->className . '/' . $fileName ;
	}

}
