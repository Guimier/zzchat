<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for LastPostAjaxQueryPart. */
class LastPostsAjaxQueryPartTest extends ClassTester
{
	/** @expectedException WebMissingParameterException */
	public function testWithoutParameter()
	{
		$this->runAjax() ;
	}

	public function testWithoutChannelsidsParameter()
	{
		$res = $this->runAjax( array( 'p_from' => '10' ) ) ;
		
		$this->assertEquals( $res, array(
			'date' => 42,
			'posts' => array()
			)
		) ; 
	}
	
}
