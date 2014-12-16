<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/common/Autoloader.php' ;
require_once __DIR__ . '/trees.php' ;

/** Helper for unit testing.
 * Will load the class, provide test data abstraction and load any exception
 * class the tested class needs.
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
	 * Logical place for part of this would be setUpBeforeClass, but setUpBeforeClass
	 * is called before #run.
	 */
	public function setUp()
	{
		spl_autoload_register( 'ClassTester::loadRestricted' ) ;
		$dir = __DIR__ . '/' . $this->className ;
		
		$this->load( 'JSON' );
		$this->load( 'Configuration' ) ;
		$this->load( 'Context' ) ;
		$this->load( 'Languages' ) ;
		require_once __DIR__ . '/TestContext.php' ;
		Context::setCanonical( new TestContext() ) ;
		
		$reqData = $this->getRootDir() . '/tests/' . $this->className . '/data' ;
		$tmpData = $this->getRootDir() . '/tests/' . $this->className . '/testdata' ;
		if ( is_dir( $reqData ) )
		{
			cpTree( $reqData, $tmpData ) ;
		}
		Configuration::initiate( self::getRootDir(), 'tests/' . $this->className . '/testdata' ) ;
		
		$this->tryLoad( "$dir/placeholders.php" ) ;
		$this->load( $this->className ) ;
		$this->tryLoad( "$dir/extenders.php" ) ;
	}

	/** Delete the temporary files. */
	public function tearDown()
	{
		$tmpData = $this->getRootDir() . '/tests/' . $this->className . '/testdata' ;
		if ( is_dir( $tmpData ) )
		{
			rmTree( $tmpData ) ;
		}
	}

	/** Conditionnaly skip a test.
	 * @param boolean $bool Whether or not to skip the test.
	 */
	public function skipIf( $bool )
	{
		if ( $bool )
		{
			$this->markTestIncomplete();
		}
	}

/***** Partial autoloading *****/
	
	/** Autoloader for classes. */
	private static $autoloader = null ;

	/** Load a class (unrestricted access to the autoloader).
	 *
	 * @param string $className Class to load.
	 */
	private static function load( $className )
	{
		if ( self::$autoloader == null )
		{
			self::$autoloader = new Autoloader( self::getRootDir(), 'common/classes.json' ) ;
		}
		self::$autoloader->load( $className ) ;
	}

	/** Load a class (restricted access to the autoloader).
	 *
	 * @param string $className Class to load.
	 */
	public static function loadRestricted( $className )
	{
		if ( substr( $className, -9 ) === 'Exception' )
		{
			self::load( $className ) ;
		}
	}

/***** Paths *****/

	/** Get full path to root directory of Agora. */
	private static function getRootDir()
	{
		return dirname( __DIR__ ) ;
	}

	/** Get relative path to the directory where test data is. */
	public function getRelDataDir()
	{
		return 'tests/' . $this->className . '/testdata' ;
	}

}
