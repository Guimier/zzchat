<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for Languages. */
class Languages_Test extends ClassTester
{
	
	public function testLanguagesList()
	{
		$langs = new _Languages( $this->getRelDataDir() ) ;
		
		$this->assertEquals(
			$langs->getAllLanguages(),
			array(
				'en' => 'English',
				'fr' => 'français',
				'empty' => 'Empty',
				'absent' => '#[lang.absent]#'
			),
			"Languages::getAllLanguages is supposed to return an array containing the languages names (in the languages themselves)."
		) ;
	}
	
	public function testMessages()
	{
		$langs = new _Languages( $this->getRelDataDir() ) ;
		
		$this->assertEquals(
			$langs->getMessage( 'fr', 'msg.arg', array(
				'argument' => 'foo'
			) ),
			'Message avec foo',
			'Message must be in French and contain “foo”.'
		) ;
		
		$this->assertEquals(
			$langs->getMessage( 'empty', 'msg.arg', array(
				'argument' => 'foo'
			) ),
			'Message with foo; repeat: foo',
			'Message must be in English (default) and contain “foo” twice.'
		) ;
		
		$this->assertEquals(
			$langs->getMessage( 'en', 'absent' ),
			'#[absent]#',
			'Message must be in English (default) and contain “foo” twice.'
		) ;
		
		$this->assertEquals(
			$langs->getMessage( 'en', 'msg.args', array(
				'two' => 'foo',
				'argument' => 'bar'
			) ),
			'Message with foo bar',
			'Message must be in English (default) and contain “foo”, then “bar”.'
		) ;
	}
	
	public function testAllMessages()
	{
		$langs = new _Languages( $this->getRelDataDir() ) ;
		
		$excludes = array( 'lang.', 'msg.args' ) ;
		
		$this->assertEquals(
			$langs->getAllMessages( 'fr', false ),
			array(
				'lang.fr' => 'français',
				'msg.arg' => 'Message avec ${argument}'
			),
			'Unfiltered list of messages without defaults should contain all _raw_ messages.'
		) ;
		
		$this->assertEquals(
			$langs->getAllMessages( 'fr', true ),
			array(
				'lang.fr' => 'français',
				'msg.arg' => 'Message avec ${argument}',     					'lang.en' => 'English',
				'lang.empty' => 'Empty',
				'msg.noargs' => 'Message without argument',
				'msg.args' => 'Message with ${two} ${argument}'
			),
			'Unfiltered list of messages with defaults should contain all _raw_ messages, some of which may be in English (default).'
		) ;
		
		$this->assertEquals(
			$langs->getAllMessages( 'fr', false, $excludes ),
			array(
				'msg.arg' => 'Message avec ${argument}'
			),
			'Filtered list of messages without defaults should contain only messages without the specified prefixes.'
		) ;
		
		$this->assertEquals(
			$langs->getAllMessages( 'fr', true, $excludes ),
			array(
				'msg.arg' => 'Message avec ${argument}',
				'msg.noargs' => 'Message without argument'
			),
			'Unfiltered list of messages with defaults should contain only messages without the specified prefixes, some of which may be in English (default).'
		) ;
	}
	
}


