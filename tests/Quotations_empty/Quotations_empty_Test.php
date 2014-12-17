<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for Quotations. */
class Quotations_empty_Test extends ClassTester
{

/*----- Get -----*/

	/** @expectedException BadCallException */
	public function testGet()
	{
		$qs = new Quotations() ;
		$qs->get( 0 ) ;
	}

/*----- GetAll -----*/

	public function testGetAll()
	{
		$qs = new Quotations() ;
		
		$this->assertEquals( $qs->getAll(), array() ) ;
	}

/*----- GetRandom -----*/

	public function testGetRandom()
	{
		$qs = new Quotations() ;
		
		$this->assertEquals( $qs->getRandom(), null ) ;
	}

/*----- Add -----*/

	public function testAddWithoutAuthor()
	{
		$qs = new Quotations() ;
		
		$qs->add( 'Yeah!', null ) ;
		$qs = null ; // Call destructor
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getLocalDir() . '/quotations.json' ),
			array(
				array( 'text' => 'Yeah!', 'author' => null )
			)
		) ;
	}
	
	public function testAddWithAuthor()
	{
		$qs = new Quotations() ;
		
		$qs->add( 'Yeah!', 'Someone' ) ;
		$qs = null ; // Call destructor
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getLocalDir() . '/quotations.json' ),
			array(
				array( 'text' => 'Yeah!', 'author' => 'Someone' )
			)
		) ;
	}

/*----- Remove -----*/

	/** @expectedException BadCallException */
	public function testRemove()
	{
		$qs = new Quotations() ;
		$qs->remove( 0 ) ;
	}
}
