<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for Configuration.
 * @codeCoverageIgnore
 */
class ConfigurationTest extends ClassTester
{
	
	private function getInstance()
	{
		return Configuration::setInstance(
			$this->getTestDataDir(),
			'default.json',
			'local.json'
		) ;
	}
	
	/** Test of pathes given. */
	public function testPathes()
	{
		$config = $this->getInstance() ;
		
		$this->assertEquals(
			$config->getDataDir( 'someKey' ),
			$this->getTestDataDir() . '/data/someKey',
			'Root path should not change after construction.'
		) ;
	}
	
	/** Test of configuration access. */
	public function testConfigurationAccess()
	{
		$config = $this->getInstance() ;
		
		$this->assertEquals(
			$config->getValue( 'existant-defaultonly' ),
			123456,
			'Default value must be given if not overriden.'
		) ;
		
		$this->assertEquals(
			$config->getValue( 'existant-redefined' ),
			963852,
			'Values must be overriden if types match.'
		) ;
		
		$this->assertEquals(
			$config->getValue( 'existant-badtype' ),
			789123,
			'Values must be not overriden if types don’t match.'
		) ;
	}
	
	/** Test of bad configuration access when the key has been defined by the administrator.
	 * @expectedException NoSuchConfigurationKeyException
	 */
	public function testConfigurationDefinedBadKeyAccess()
	{
		$config = $this->getInstance() ;
		
		$config->getValue( 'nonexistant-defined' ) ;
	}
	
	/** Test of bad configuration access when the key has not been defined by the administrator.
	 * @expectedException NoSuchConfigurationKeyException
	 */
	public function testConfigurationBadKeyAccess()
	{
		$config = $this->getInstance() ;
		
		$config->getValue( 'nonexistant-nondefined' ) ;
	}
}


