<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for LastPostAjaxQueryPart. */
class LastPostsAjaxQueryPartTest extends ClassTester
{
	public function testWithoutChannelsidsParameter()
	{
		$res = $this->runAjax( array( 'p_from' => '10' ) ) ;
		
		$this->assertEquals( $res, array(
			'date' => 42,
			'posts' => array()
			)
		) ; 
	}
	
	/** @expectedException WebMissingParameterException */
	public function testWithoutFromParameter()
	{
		$this->runAjax( array( 'p_channels' => '1,2,3' ) ) ;		
	}
	
	public function testExecute()
	{
		$res = $this->runAjax( array( 'p_channels' => '1,2', 'p_from' => '10' ) ) ;
		
		$this->assertEquals(
			$res,
			array ( 
				'date' => 42,
				'posts' => array(
					'1' => array(
						array( 'id' => 1, 'owner' => array( 'id' => 18, 'name' => 'A user' ), 'date' => 43, 'content' => 'Bonjour1' ),
						array( 'id' => 2, 'owner' => array( 'id' => 18, 'name' => 'A user' ), 'date' => 44, 'content' => 'Bonjour2' )
					),
					'2' => array(
						array( 'id' => 1, 'owner' => array( 'id' => 18, 'name' => 'A user' ), 'date' => 43, 'content' => 'Bonjour1' ),
						array( 'id' => 2, 'owner' => array( 'id' => 18, 'name' => 'A user' ), 'date' => 44, 'content' => 'Bonjour2' ),
					)
				)
			)
		) ;	
	}
	
}
