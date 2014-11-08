<?php

require_once dirname( __DIR__ ) . '/back/Autoloader.php' ;

/** Helper for unit testing.
 * Will load the class, provide test data abstraction and load any exception
 * class the tested class needs.
 *
 * @codeCoverageIgnore
 */
class ClassTester extends PHPUnit_Framework_TestCase
{
	
	/** Name of the class being tested. */
	private $className ;

	/** Constructor.
	 */
	public function __construct( $name = NULL, array $data = array(), $dataName = '' )
	{
		parent::__construct( $name, $data, $dataName ) ;
		$this->className = substr( get_class( $this ), 0, -4 ) ;
	}

	/** Wrapper for PHPUnit_Framework_TestCase::run
	 * @see http://matthewturland.com/2010/08/19/process-isolation-in-phpunit/
	 */
	public function run( PHPUnit_Framework_TestResult $result = NULL )
	{
		$this->setPreserveGlobalState( false );
		return parent::run( $result );
	}

	/** Load a file if it exists.
	 * @param string $file Full path to the file.
	 */
	private function tryLoad( $file )
	{
		if ( file_exists( $file ) )
		{
			require_once $file ;
		}
	}

	/** Load the tested class.
	 * Logical place for this would be setUpBeforeClass, but setUpBeforeClass
	 * is called before #run.
	 */
	public function setUp()
	{
		$dir = __DIR__ . '/' . $this->className ;
		$this->tryLoad( "$dir/placeholders.php" ) ;
		$this->startAutoload() ;
		$this->tryLoad( "$dir/extenders.php" ) ;
	}

/***** Partial autoloading *****/
	
	/** Autoloader for classes. */
	private static $autoloader = null ;

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
		if ( substr( $className, -9 ) === 'Exception' )
		{
			self::load( $className ) ;
		}
	}
	
	/** Load the tested class and its parents. */
	private function startAutoload()
	{
		spl_autoload_register( array( $this, 'autoload' ) ) ;
		self::load( $this->className ) ;
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
		return $this->getRootDir() . '/tests/' . $this->className . '/data' ;
	}

}

