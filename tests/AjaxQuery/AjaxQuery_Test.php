<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for AjaxQuery. */
class AjaxQuery_Test extends ClassTester
{
	
	/** Test for headers */
	private function headersAssertions()
	{
		$this->skipIf( ! function_exists ( 'xdebug_get_headers' ) ) ;

		$this->assertContains(
			'Content-Type: application/json; charset=UTF-8',
			xdebug_get_headers(),
			'AjaxQuery must return UTF-8 JSON.'
		) ;
	}
	
	/** Test execution with non-indented output. */
	public function testNonIndented()
	{
		$query = new AjaxQuery( new WebContext( false ) ) ;

		$expected = JSON::encode( array(
			"working" => array(
				"success" => true,
				"data" => array( "foo","ß","×" )
			),
			"workingNull" => array(
				"success" => true
			),
			"workingEmpty" => array(
				"success" => true,
				"data" => array()
			),
			"throwingUser" => array(
				"success" => false,
				"type" => "user",
				"error" => "GenericAgoraUserException",
				"message" => "#[baz]#",
				"struct" => array(
					"message" => "baz",
					"arguments" => array()
				)
			),
			"throwingInternal" => array(
				"success" => false,
				"type" => "internal"
			),
			"nonexistant" => array(
				"success" => false,
				"type" => "user",
				"error" => "NoSuchQueryPartException",
				"message" => "“nonexistant” query part does not exist",
				"struct" => array(
					"message" => "exceptions.nosuchquerypart",
					"arguments" => array(
						"partname" => "nonexistant"
					)
				)
			)
		) ) ;

		$query->execute() ;
		$query->show() ;
		$this->expectOutputString( $expected ) ;
		
		$this->headersAssertions() ;
	}
}
