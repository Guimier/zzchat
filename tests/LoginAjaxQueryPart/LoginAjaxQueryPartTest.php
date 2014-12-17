<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for LogoutAjaxQueryPart. */
class LoginAjaxQueryPartTest extends ClassTester
{

	/** @expectedException WebMissingParameterException */
	public function testNoName()
	{
		$this->runAjax() ;
	}

	/** @expectedException WebMissingParameterException */
	public function testWithBadParameterType()
	{
		$this->runAjax( array( 'p_name' => 'Un nom' ) ) ;
	}

	public function testWithName()
	{
		$this->runAjax( array(), array( 'p_name' => 'Un nom' ) ) ;
		$this->assertEquals(
			$this->getSession(),
			array( 'user-id' => 18 )
		) ;
	}

}
