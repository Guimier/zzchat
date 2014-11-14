<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for Context.
 * @codeCoverageIgnore
 */
class ContextTest extends ClassTester
{
	
	/** Test canonical reference. */
	public function testCanonical()
	{
		$c1 = new _Context() ;
		$c2 = new _Context() ;
		
		Context::setCanonical( $c1 ) ;
		Context::setCanonical( $c2 ) ;
		
		$this->assertEquals(
			Context::getCanonical(),
			$c2,
			'The canonical context can change.'
		) ;
	}
	
	/** Test access to undefined canonical reference.
	 * @expectedException
	 */
	public function testUndefinedCanonical()
	{
		Context::getCanonical() ;
	}
	
	/** Test strings values. */
	public function testStringParameter()
	{
		$context = new _Context() ;
		
		$this->assertEquals(
			$context->getParameter( 'foo' ),
			'bar',
			'String value of a string value is itself.'
		) ;
		
		$this->assertNull(
			$context->getParameter( 'null' ),
			'String value of a null value is null.'
		) ;
	}
	
	/** Test boolean values. */
	public function testBooleanParameter()
	{
		$context = new _Context() ;
		
		$this->assertTrue(
			$context->getBooleanParameter( 'foo' ),
			'Boolean value of a string value is true.'
		) ;
		
		$this->assertTrue(
			$context->getBooleanParameter( 'empty' ),
			'Boolean value of an empty string value is true.'
		) ;
		
		$this->assertFalse(
			$context->getBooleanParameter( 'null' ),
			'Boolean value of a null value is false.'
		) ;
	}
	
	/** Test array values. */
	public function testArrayParameter()
	{
		$context = new _Context() ;
		
		$this->assertEquals(
			$context->getArrayParameter( 'foo' ),
			array( 'bar' ),
			'Array value of a simple string value is an array with this string as only element.'
		) ;
		
		$this->assertEquals(
			$context->getArrayParameter( 'list' ),
			array( 'foo', 'bar', 'baz' ),
			'Array value of a list is an array containing the elements of the list.'
		) ;
		
		$this->assertEquals(
			$context->getArrayParameter( 'empty' ),
			array( '' ),
			'Array value of an empty string value is an array with the empty string as only element.'
		) ;
		
		$this->assertNull(
			$context->getArrayParameter( 'null' ),
			'Array value of a null value is null.'
		) ;
	}
}


