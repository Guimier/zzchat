<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for LogoutAjaxQueryPart. */
class LogoutAjaxQueryPartTest extends ClassTester
{

	/** @expectedException NotLoggedInUserException */
	public function testUnconnected()
	{
		$this->runAjax() ;
	}

	public function testConnected()
	{
		$this->setSession( array( 'user-id' => 18 ) ) ;
		$this->runAjax() ;
		$this->assertEquals(
			$this->getSession(),
			array()
		) ;
	}

}
