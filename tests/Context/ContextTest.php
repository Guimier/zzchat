<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for Context.
 * @codeCoverageIgnore
 */
class ContextTest extends ClassTester
{
	
	/** Test of pathes given. */
	public function testPathes()
	{
		$context = new _Context( $this->getTestDataDir() ) ;
		
		$this->assertEquals(
			$context->getDataDir( 'someKey' ),
			$this->getTestDataDir() . '/data/someKey',
			'Root path must not have changed since construction'
		) ;
	}
	
	/** Test of configuration access. */
	public function testConfigurationAccess()
	{
		$context = new _Context( $this->getTestDataDir() ) ;
		
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
		$context = new _Context( $this->getTestDataDir() ) ;
		
		$context->getConf( 'nonexistant' ) ;
	}
	
	/** Test of bad configuration access when the key has not been defined by the administrator.
	 * @expectedException NoSuchConfigurationKeyException
	 */
	public function testConfigurationBadKeyAccess()
	{
		$context = new _Context( $this->getTestDataDir() ) ;
		
		$context->getConf( 'nonexistant-nondefined' ) ;
	}
}
