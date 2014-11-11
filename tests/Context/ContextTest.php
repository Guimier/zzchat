<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for Context.
 * @codeCoverageIgnore
 */
class ContextTest extends ClassTester
{
	
	
	
	/** Test strings values. */
	public function testStringParameter()
	{
		$params = new _Context() ;
		
		$this->assertEquals(
			$params->getParameter( 'foo' ),
			'bar',
			'String value of a string value is itself.'
		) ;
		
		$this->assertNull(
			$params->getParameter( 'null' ),
			'String value of a null value is null.'
		) ;
	}
	
	/** Test boolean values. */
	public function testBooleanParameter()
	{
		$params = new _Context() ;
		
		$this->assertTrue(
			$params->getBooleanParameter( 'foo' ),
			'Boolean value of a string value is true.'
		) ;
		
		$this->assertTrue(
			$params->getBooleanParameter( 'empty' ),
			'Boolean value of an empty string value is true.'
		) ;
		
		$this->assertFalse(
			$params->getBooleanParameter( 'null' ),
			'Boolean value of a null value is false.'
		) ;
	}
	
	/** Test array values. */
	public function testArrayParameter()
	{
		$params = new _Context() ;
		
		$this->assertEquals(
			$params->getArrayParameter( 'foo' ),
			array( 'bar' ),
			'Array value of a simple string value is an array with this string as only element.'
		) ;
		
		$this->assertEquals(
			$params->getArrayParameter( 'list' ),
			array( 'foo', 'bar', 'baz' ),
			'Array value of a list is an array containing the elements of the list.'
		) ;
		
		$this->assertEquals(
			$params->getArrayParameter( 'empty' ),
			array( '' ),
			'Array value of an empty string value is an array with the empty string as only element.'
		) ;
		
		$this->assertNull(
			$params->getArrayParameter( 'null' ),
			'Array value of a null value is null.'
		) ;
	}
}


