<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for Configuration. */
class ConfigurationTest extends ClassTester
{
	
	public function setUp()
	{
		parent::setUp() ;
		
		Configuration::initiate(
			$this->getTestDataDir(),
			'default.json',
			'local.json'
		) ;
	}
	
	/** Test of pathes given. */
	public function testPathes()
	{
		$this->assertEquals(
			Configuration::getDataDir( 'someKey' ),
			'/local/data/someKey',
			'Data subdirectories are in “local/data” directory.'
		) ;
	}
	
	/** Test of configuration access. */
	public function testConfigurationAccess()
	{
		$this->assertEquals(
			Configuration::getValue( 'existant-defaultonly' ),
			123456,
			'Default value must be given if not overriden.'
		) ;
		
		$this->assertEquals(
			Configuration::getValue( 'existant-redefined' ),
			963852,
			'Values must be overriden if types match.'
		) ;
		
		$this->assertEquals(
			Configuration::getValue( 'existant-badtype' ),
			789123,
			'Values must be not overriden if types don’t match.'
		) ;
	}
	
	/** Test of bad configuration access when the key has been defined by the administrator.
	 * @expectedException NoSuchConfigurationKeyException
	 */
	public function testConfigurationDefinedBadKeyAccess()
	{
		Configuration::getValue( 'nonexistant-defined' ) ;
	}
	
	/** Test of bad configuration access when the key has not been defined by the administrator.
	 * @expectedException NoSuchConfigurationKeyException
	 */
	public function testConfigurationBadKeyAccess()
	{
		Configuration::getValue( 'nonexistant-nondefined' ) ;
	}
}


