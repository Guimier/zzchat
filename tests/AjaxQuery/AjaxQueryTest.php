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
		if ( function_exists ( 'xdebug_get_headers' ) )
		{
			$this->assertContains(
				'Content-Type: application/json; charset=UTF-8',
				xdebug_get_headers(),
				'AjaxQuery must return UTF-8 JSON.'
			) ;
		}
		else
		{
			trigger_error( 'Xdebug required for headers tests.', E_USER_WARNING ) ;
		}
	}
	
	/** Test execution with non-indented output. */
	public function testNonIndented()
	{
		$query = new AjaxQuery( new NonIndentedContext() ) ;
		
		$query->execute() ;
		$query->show() ;
		$this->expectOutputString(
<<<JSON
{"working":{"success":true,"data":["foo","ß","×"]},"throwing":{"success":false,"error":"GenericException"},"workingNull":{"success":true},"nonexistant":{"success":false,"error":"NoSuchQueryPartException"}}
JSON
		) ;
		
		$this->headersAssertions() ;
	}
	
	/** Test execution with indented output. */
	public function testIndented()
	{
		$query = new AjaxQuery( new IndentedContext() ) ;
		
		$query->execute() ;
		$query->show() ;
		
		$this->expectOutputString(
<<<JSON
{
    "working": {
        "success": true,
        "data": [
            "foo",
            "ß",
            "×"
        ]
    },
    "throwing": {
        "success": false,
        "error": "GenericException"
    },
    "workingNull": {
        "success": true
    },
    "nonexistant": {
        "success": false,
        "error": "NoSuchQueryPartException"
    }
}
JSON
		) ;
		
		$this->headersAssertions() ;
	}
}
