<?php

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for CmdContext.
 * @codeCoverageIgnore
 */
class CmdParametersTest extends ClassTester
{
	
	/** Canonical case. */
	public function testCanonicalCase()
	{
		$context = new CmdParameters( array(
				'<file.php>',
				'foo1',
				'--bar',
				'--baz=qux',
				'foo2'
		) ) ;
		
		$this->assertEquals(
			$context->getValue( 0, null ),
			'foo1',
			'First unnamed argument is “foo1”.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 1, null ),
			'foo2',
			'Second unnamed argument is “foo2”.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'bar', null ),
			'',
			'“bar” is an empty nammed argument.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'baz', null ),
			'qux',
			'“baz” is an nammed argument whose value is “qux”.'
		) ;
	}
	
	/** Test of only-unnamed parameters. */
	public function testOnlyUnnamed()
	{
		$context = new CmdParameters( array(
			'<file.php>',
			'--',
			'foo',
			'--bar',
			'--baz=qux'
		) ) ;
		
		$this->assertEquals(
			$context->getValue( 0, null ),
			'foo',
			'First real argument is “foo”.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 1, null ),
			'--bar',
			'“--bar” after “--” is not an empty named argument.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 2, null ),
			'--baz=qux',
			'“--baz=qux” after “--” is not a named argument.'
		) ;
	}
	
	/** Test of mixed modes */
	public function testMixed()
	{
		$context = new CmdParameters( array(
			'<file.php>',
			'foo1',
			'--bar1',
			'--baz1=qux1',
			'--',
			'foo2',
			'--bar2',
			'--baz2=qux2'
		) ) ;
		
		$this->assertEquals(
			$context->getValue( 0, null ),
			'foo1',
			'First argument is “foo1”.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 1, null ),
			'foo2',
			'Second argument is “foo2”.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 2, null ),
			'--bar2',
			'“--bar2” after “--” is not an empty named argument.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 3, null ),
			'--baz2=qux2',
			'“--baz2=qux2” after “--” is not a named argument.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'bar1', null ),
			'',
			'“bar1” before “--” is an empty nammed argument.'
		) ;
		
		$this->assertEquals(
			$context->getValue( 'baz1', null ),
			'qux1',
			'“baz1” before “--” is an nammed argument whose value is “qux”.'
		) ;
	}
}
