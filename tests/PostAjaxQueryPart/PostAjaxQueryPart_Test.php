<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for LastPostAjaxQueryPart. */
class PostAjaxQueryPart_Test extends ClassTester
{
	public function testCanonical()
	{
		$this->setSession( array( 'user-id' => 18 ) ) ;
		
		$this->runAjax( array(), array( 'p_channel' => '1', 'p_content' => 'Bonjour' ) ) ;
		
		$this->assertEquals(
			Channel::$calls,
			array(
				array( 'addPost', 'user' => 18, 'content' => 'Bonjour' )
			)
		) ;
	}
	
	/** @expectedException WebMissingParameterException */
	public function testWithoutContentParameter()
	{
		$this->setSession( array( 'user-id' => 18 ) ) ;
		
		$this->runAjax( array(), array( 'p_channel' => '1' ) ) ;
	}
	
	/** @expectedException WebMissingParameterException */
	public function testGetParameters()
	{
		$this->setSession( array( 'user-id' => 18 ) ) ;
		
		$this->runAjax( array( 'p_channel' => '1', 'p_content' => 'Bonjour' ) ) ;
	}
	
	/** @expectedException WebMissingParameterException */
	public function testWithoutChannelId()
	{
		$this->setSession( array( 'user-id' => 18 ) ) ;
		
		$this->runAjax( array(), array( 'p_content' => 'Bonjour' ) ) ;
		
		$this->assertEquals(
			Channel::$calls,
			array()
		) ;
	}
	
}
