<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/**
 * Test for Parameters.
 * We may want to test shortcuts here in the future.
 * @codeCoverageIgnore
 */
class ParametersTest extends ClassTester
{
	
	/** Test strings values. */
	public function testStringValue()
	{
		$params = new _Parameters() ;
		
		$this->assertEquals(
			$params->getValue( 'foo' ),
			'bar',
			'String value of a string value is itself.'
		) ;
		
		$this->assertNull(
			$params->getValue( 'null' ),
			'String value of a null value is null.'
		) ;
	}
	
	/** Test boolean values. */
	public function testBooleanValue()
	{
		$params = new _Parameters() ;
		
		$this->assertTrue(
			$params->getBooleanValue( 'foo' ),
			'Boolean value of a string value is true.'
		) ;
		
		$this->assertTrue(
			$params->getBooleanValue( 'empty' ),
			'Boolean value of an empty string value is true.'
		) ;
		
		$this->assertFalse(
			$params->getBooleanValue( 'null' ),
			'Boolean value of a null value is false.'
		) ;
	}
	
	/** Test array values. */
	public function testArrayValue()
	{
		$params = new _Parameters() ;
		
		$this->assertEquals(
			$params->getValues( 'foo' ),
			array( 'bar' ),
			'Array value of a simple string value is an array with this string as only element.'
		) ;
		
		$this->assertEquals(
			$params->getValues( 'list' ),
			array( 'foo', 'bar', 'baz' ),
			'Array value of a list is an array containing the elements of the list.'
		) ;
		
		$this->assertEquals(
			$params->getValues( 'empty' ),
			array( '' ),
			'Array value of an empty string value is an array with the empty string as only element.'
		) ;
		
		$this->assertNull(
			$params->getValues( 'null' ),
			'Array value of a null value is null.'
		) ;
	}
	
}


