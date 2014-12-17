<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for  WhoamiAjaxQueryPart. */
class WhoamiAjaxQueryPart_Test extends ClassTester
{

	public function testUnconnected()
	{
		$this->assertNull( $this->runAjax( array() ) ) ;
	}

	public function testConnected()
	{
		require_once __DIR__ . '/placeholders.php' ;
		$this->setSession( array( 'user-id' => 18 ) ) ;
		
		$this->assertEquals(
			$this->runAjax( array() ),
			array(
				'id' => 18,
				'name' => 'A user'
			)
		) ;
	}

}
