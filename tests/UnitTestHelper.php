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
		$this->load( $className ) ;
	}

/***** Partial autoloading *****/
	
	/** Autoloader for classes. */
	private static $autoloader = null ;
	
	/** Is the object allowed to load more classes.
	 * Exceptions loading is not affected by the value.
	 */
	private $loadAny = false ;

	/** Load a class (unrestricted access to the autoloader).
	 *
	 * @param string $className Class to load.
	 */
	public static function load( $className )
	{
		if ( self::$autoloader == null )
		{
			self::$autoloader = new Autoloader( self::getRootDir(), 'back/classes.json' ) ;
		}
		self::$autoloader->load( $className ) ;
	}

	/** Load a class (restricted access to the autoloader).
	 *
	 * @param string $className Class to load.
	 */
	public function autoload( $className )
	{
		if ( $this->loadAny || substr( $className, -9 ) === 'Exception' )
		{
			$this->load( $className ) ;
		}
	}
	
	/** Load the tested class and its parents. */
	private function startAutoload()
	{
		$this->loadAny = true ;
		$this->loadClass( $this->className ) ;
		$this->loadAny = false ;
	}

/***** Paths *****/

	/** Get full path to root directory of Agora. */
	private static function getRootDir()
	{
		return dirname( __DIR__ ) ;
	}

	/** Get full path to the directory where test data is. */
	public function getTestDataDir()
	{
		return $this->getRootDir() . '/tests/data/' . $this->className ;
	}

}

