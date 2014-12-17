<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for Quotations. */
class QuotationsTest extends ClassTester
{

/*----- Get -----*/

	public function testGetWithoutAuthor()
	{
		$qs = new Quotations() ;
		
		$this->assertEquals(
			$qs->get( 2 ),
			array( 'text' => 'Always more quotations', 'author' => null )
		) ;
	}
	
	public function testGetWithAuthor()
	{
		$qs = new Quotations() ;
		
		$this->assertEquals(
			$qs->get( 0 ),
			array( 'text' => 'Some quotation', 'author' => 'Me' )
		) ;
	}

	/** @expectedException BadCallException */
	public function testGetInexistant()
	{
		$qs = new Quotations() ;
		$qs->get( 32 ) ;
	}

/*----- GetAll -----*/

	public function testGetAll()
	{
		$qs = new Quotations() ;
		
		$this->assertEquals(
			$qs->getAll(),
			array(
				array( 'text' => 'Some quotation', 'author' => 'Me' ),
				array( 'text' => 'Some other quotation', 'author' => 'You' ),
				array( 'text' => 'Always more quotations', 'author' => null )
			)
		) ;
	}

/*----- GetRandom -----*/

	public function testGetRandom()
	{
		$qs = new Quotations() ;
		
		$this->assertContains(
			$qs->getRandom(),
			array(
				array( 'text' => 'Some quotation', 'author' => 'Me' ),
				array( 'text' => 'Some other quotation', 'author' => 'You' ),
				array( 'text' => 'Always more quotations', 'author' => null )
			)
		) ;
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
				array( 'text' => 'Some quotation', 'author' => 'Me' ),
				array( 'text' => 'Some other quotation', 'author' => 'You' ),
				array( 'text' => 'Always more quotations', 'author' => null ),
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
				array( 'text' => 'Some quotation', 'author' => 'Me' ),
				array( 'text' => 'Some other quotation', 'author' => 'You' ),
				array( 'text' => 'Always more quotations', 'author' => null ),
				array( 'text' => 'Yeah!', 'author' => 'Someone' )
			)
		) ;
	}

/*----- Remove -----*/

	public function testRemoveFirst()
	{
		$qs = new Quotations() ;
		
		$qs->remove( 0 ) ;
		$qs = null ; // Call destructor
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getLocalDir() . '/quotations.json' ),
			array(
				array( 'text' => 'Some other quotation', 'author' => 'You' ),
				array( 'text' => 'Always more quotations', 'author' => null )
			)
		) ;
	}

	public function testRemoveLast()
	{
		$qs = new Quotations() ;
		
		$qs->remove( 2 ) ;
		$qs = null ; // Call destructor
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getLocalDir() . '/quotations.json' ),
			array(
				array( 'text' => 'Some quotation', 'author' => 'Me' ),
				array( 'text' => 'Some other quotation', 'author' => 'You' )
			)
		) ;
	}

	public function testRemoveAny()
	{
		$qs = new Quotations() ;
		
		$qs->remove( 1 ) ;
		$qs = null ; // Call destructor
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getLocalDir() . '/quotations.json' ),
			array(
				array( 'text' => 'Some quotation', 'author' => 'Me' ),
				array( 'text' => 'Always more quotations', 'author' => null )
			)
		) ;
	}

	public function testRemoveAll()
	{
		$qs = new Quotations() ;
		
		$qs->remove( 0 ) ;
		$qs->remove( 1 ) ;
		$qs->remove( 2 ) ;
		$qs = null ; // Call destructor
		
		$this->assertEquals(
			Configuration::loadJson( Configuration::getLocalDir() . '/quotations.json' ),
			array()
		) ;
	}

	/** @expectedException BadCallException */
	public function testRemoveInexistant()
	{
		$qs = new Quotations() ;
		$qs->remove( 32 ) ;
	}
}
