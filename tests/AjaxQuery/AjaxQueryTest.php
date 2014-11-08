<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for AjaxQuery.
 * @codeCoverageIgnore
 */
class AjaxQueryTest extends ClassTester
{
	
	/** Test execution with non-indented output. */
	public function testNonIndented()
	{
		$query = new AjaxQuery( new NonIndentedContext() ) ;
		
		$query->execute() ;
		$query->show() ;
		$this->expectOutputString(
<<<JSON
{"working":{"success":true,"data":["foo","bar"]},"throwing":{"success":false,"error":"GenericException"},"workingNull":{"success":true},"nonexistant":{"success":false,"error":"NoSuchQueryPartException"}}
JSON
		) ;
		
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
            "bar"
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
	}
}
