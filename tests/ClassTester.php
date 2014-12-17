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
	
	/** Name of the test directory. */
	private $dirName ;
	
	/** Name of the class being tested. */
	private $className ;

	/** Constructor. */
	public function __construct( $name = NULL, array $data = array(), $dataName = '' )
	{
		parent::__construct( $name, $data, $dataName ) ;
		$this->className = preg_replace( '/^([A-Za-z]+)_.+$/', '\1', get_class( $this ) ) ;
		$this->dirName = preg_replace( '/^(.+)_[A-Za-z]+$/', '\1', get_class( $this ) ) ;
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

	/** Are we loading a class hierarchy? */
	private static $loading = false ;

	/** Load the tested class.
	 * Logical place for part of this would be setUpBeforeClass, but setUpBeforeClass
	 * is called before #run.
	 */
	public function setUp()
	{
		spl_autoload_register( 'ClassTester::loadRestricted' ) ;
		$dir = __DIR__ . '/' . $this->dirName ;
		
		$this->load( 'JSON' );
		$this->load( 'Configuration' ) ;
		$this->load( 'Context' ) ;
		$this->load( 'Languages' ) ;
		require_once __DIR__ . '/TestContext.php' ;
		Context::setCanonical( new TestContext() ) ;
		
		$reqData = "$dir/data" ;
		$tmpData = "$dir/testdata" ;
		if ( is_dir( $reqData ) )
		{
			cpTree( $reqData, $tmpData ) ;
		}
		Configuration::initiate( self::getRootDir(), 'tests/' . $this->dirName . '/testdata' ) ;
		
		$this->tryLoad( "$dir/placeholders.php" ) ;
		self::$loading = true ;
		$this->load( $this->className ) ;
		self::$loading = false ;
		$this->tryLoad( "$dir/extenders.php" ) ;
	}

	/** Delete the temporary files. */
	public function tearDown()
	{
		$tmpData = $this->getRootDir() . '/tests/' . $this->dirName . '/testdata' ;
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
	 * This method is now public: load any **core** class you need.
	 * @param string $className Class to load.
	 */
	public static function load( $className )
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
		if (
			self::$loading
			|| substr( $className, -9 ) === 'Exception'
			|| substr( $className, -8 ) === 'Formater'
		)
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
		return 'tests/' . $this->dirName . '/testdata' ;
	}

/***** Specific classes types. *****/

	protected function runCommand( $class /* … */ )
	{
		self::load( 'CliContext' ) ;
		$command = new $class( new CliContext( func_get_args() ), $class ) ;
		$command->execute() ;
	}

	private $session = array() ;
	
	protected function setSession( array $new ) { $this->session = $new ; }
	protected function getSession() { return $this->session  ; }

	protected function runAjax( $get = array(), $post = array() )
	{
		$class = $this->className ;
		$this->load( 'WebContext' ) ;
		require_once __DIR__ . '/TestWebContext.php' ;
		$queryPart = new $class( 'p', new TestWebContext( $get, $post, $this->session ) ) ;
		return $queryPart->execute() ;
	}

}
