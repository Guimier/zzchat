<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for AjaxQuery.
 * @codeCoverageIgnore
 */
class AjaxQueryTest extends ClassTester
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

		if ( defined( 'JSON_UNESCAPED_UNICODE' ) )
		{
			$expected = 
<<<JSON
{"working":{"success":true,"data":["foo","ß","×"]},"workingNull":{"success":true},"workingEmpty":{"success":true,"data":[]},"throwingUser":{"success":false,"error":"GenericAgoraUserException","message":"baz","type":"user"},"throwingInternal":{"success":false,"error":"GenericException","message":"baz","type":"internal"},"nonexistant":{"success":false,"error":"NoSuchQueryPartException","message":"nonexistant","type":"user"}}
JSON
			;
		}
		else
		{
			$expected = 
<<<JSON
{"working":{"success":true,"data":["foo","\u00df","\u00d7"]},"workingNull":{"success":true},"workingEmpty":{"success":true,"data":[]},"throwingUser":{"success":false,"error":"GenericAgoraUserException","message":"baz","type":"user"},"throwingInternal":{"success":false,"error":"GenericException","message":"baz","type":"internal"},"nonexistant":{"success":false,"error":"NoSuchQueryPartException","message":"nonexistant","type":"user"}}
JSON
			;
		}

		$query->execute() ;
		$query->show() ;
		$this->expectOutputString( $expected ) ;
		
		$this->headersAssertions() ;
	}
}
