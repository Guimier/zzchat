<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for User. */
class User_Test extends ClassTester
{

/*----- Creation -----*/

	/** @expectedException ContainsIllegalCharacterException */
	public function testIllegalName()
	{
		User::create( 'Æ' ) ;
	}
	
	/** @expectedException EntityNameTooShortException */
	public function testShortName()
	{
		User::create( 'A' ) ;
	}
	
	/** @expectedException EntityNameTooLongException */
	public function testLongName()
	{
		User::create( 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' ) ;
	}
	
	/** @expectedException EntityNameAlreadyTakenException */
	public function testNameInUse()
	{
		User::create( 'name-in-use' ) ;
	}
	
	public function testWorking()
	{
		User::create( 'My name' ) ;
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getDataDir( 'users' ) . '/lastid.int' ),
			5
		) ;
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getDataDir( 'users' ) . '/5.json' ),
			array(
				'logged-out' => false,
				'name' => 'My name',
				'creation' => 42,
				'last-action' => 42,
				'ejected' => false
			)
		) ;
	}
	
	/** @expectedException TooManyEntitiesException */
	public function testTooManyUsers()
	{
		User::create( 'My name' ) ;
		User::create( 'Your name' ) ;
		User::create( 'Her name' ) ;
	}
	
/*----- Specials -----*/
	
	public function testCliUser()
	{
		$this->load( 'CliUser' ) ;
		
		$this->assertInstanceOf( 'CliUser', User::getById( -1 ) ) ;
	}
	
	/** @expectedException NoSuchEntityException */
	public function testUnknownUser()
	{
		User::getById( -2 ) ;
	}
	
/*----- List of active -----*/
	
	public function testGetAllActive()
	{
		$this->assertEquals(
			User::getAllActive(),
			array(
				User::getById( 1 )
			)
		) ;
	}
	
/*----- Get active -----*/
	
	public function testGetActive()
	{
		$this->assertNull(
			User::getByName( 'inactive-time' ),
			"Inactive user must not be returned."
		) ;
		
		$this->assertEquals(
			User::getByName( 'name-in-use' ),
			User::getById( 1 ),
			"Active user must be returned."
		) ;
	}
	
/*----- Logout -----*/
	
	public function testLogout()
	{
		$this->skipIf( true ) ; // Mhh… this test works only without process isolation.
		
		User::getById( 1 )->isNowInactive() ;
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getDataDir( 'users' ) . '/1.json' ),
			array(
				'logged-out' => false,
				'name' => 'name-in-use',
				'creation' => 0,
				'last-action' => 41,
				'ejected' => false
			)
		) ;
	}
	

}
