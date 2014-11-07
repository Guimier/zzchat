<?php

require_once dirname( __DIR__ ) . '/back/Autoloader.php' ;

/** Helper for unit testing.
 * Will load the class, provide test data abstraction and load any exception
 * class the tested class needs.
 *
 * @codeCoverageIgnore
 */
class UnitTestHelper
{
	
	/** Name of the class being tested. */
	private $className ;

	/** Constructor
	 * @param string $className Name of the class that will be tested.
	 */
	public function __construct( $className )
	{
		$this->className = $className ;
		if ( $className !== 'Autoloader' )
		{
			$this->setUpAutoLogin();
		}
	}

/***** Partial autoloading *****/

	/** Object Autoloader used for partial autoload. */
	private $loader = null ;
	
	/** Is the object allowed to load more classes.
	 * Exceptions loading is not affected by the value.
	 */
	private $loadAny = false ;

	/** Load a class (restricted access to the autoloader).
	 *
	 * @param string $className Class to load.
	 */
	public function loadClass( $className )
	{
		if ( $this->loadAny || substr( $className, -9 ) === 'Exception' )
		{
			$this->loader->load( $className ) ;
		}
	}
	
	/** Build loading infrastructure and load the tested class. */
	private function setUpAutoLogin()
	{
		/* Building autoloading */
		$this->loader = new Autoloader( $this->getRootDir(), 'back/classes.json' ) ;
		spl_autoload_register( array( $this, 'loadClass' ) ) ;
		
		/* Loading tested class */
		$this->loadAny = true ; // Allow loading of parent class if needed.
		$this->loadClass( $this->className ) ;
		$this->loadAny = false ;
	}

/***** Paths *****/

	/** Get full path to root directory of Agora. */
	private function getRootDir()
	{
		return dirname( __DIR__ ) ;
	}

	/** Get full path to the directory where test data is. */
	public function getTestDataDir()
	{
		return $this->getRootDir() . '/tests/data/' . $this->className ;
	}

}

