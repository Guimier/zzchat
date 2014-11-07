<?php

require_once dirname( __DIR__ ) . '/UnitTestHelper.php' ;
UnitTestHelper::load( 'Context' ) ;
UnitTEstHelper::load( 'NoSuchConfigurationKeyException' ) ;

/** Generic child class of Context
 * @codeCoverageIgnore
 */
class GenericContext extends Context
{
	public function getParameter( $key, $more ) {}
}

/**
 * Test for Autoloader.
 * @codeCoverageIgnore
 */
class ContextTest extends PHPUnit_Framework_TestCase
{

	/** Unit-test helper for these tests */
	private static $helper ;

	/** Common test initialization.
	 * Creates the helper object.
	 */
	public static function setUpBeforeClass()
	{
		self::$helper = new UnitTestHelper( 'Context' ) ;
	}
	
	/** Test of pathes given. */
	public function testPathes()
	{
		$context = new GenericContext( self::$helper->getTestDataDir() ) ;
		
		$this->assertEquals(
			$context->getDataDir( 'someKey' ),
			self::$helper->getTestDataDir() . '/data/someKey',
			'Root path must not have changed since construction'
		) ;
	}
	
	/** Test of configuration access. */
	public function testConfigurationAccess()
	{
		$context = new GenericContext( self::$helper->getTestDataDir() ) ;
		
		$this->assertEquals(
			$context->getConf( 'user.maxpostrate' ),
			20,
			'Default value must be given if not overriden.'
		) ;
		
		$this->assertEquals(
			$context->getConf( 'channels.maxnum' ),
			4569725471,
			'Values must be overriden if types match.'
		) ;
		
		$this->assertEquals(
			$context->getConf( 'channels.inactivity' ),
			604800,
			'Values must be not overriden if types don’t match.'
		) ;
	}
	
	/** Test of bad configuration access when the key has been defined by the administrator.
	 * @expectedException NoSuchConfigurationKeyException
	 */
	public function testConfigurationDefinedBadKeyAccess()
	{
		$context = new GenericContext( self::$helper->getTestDataDir() ) ;
		
		$context->getConf( 'nonexistant' ) ;
	}
	
	/** Test of bad configuration access when the key has not been defined by the administrator.
	 * @expectedException NoSuchConfigurationKeyException
	 */
	public function testConfigurationBadKeyAccess()
	{
		$context = new GenericContext( self::$helper->getTestDataDir() ) ;
		
		$context->getConf( 'nonexistant-nondefined' ) ;
	}
}
