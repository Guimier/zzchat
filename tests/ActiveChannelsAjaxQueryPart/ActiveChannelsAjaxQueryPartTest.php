<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for ActiveChannelsAjaxQueryPart. */
class ActiveChannelsAjaxQueryPartTest extends ClassTester
{

	public function testWithName()
	{
		$this->assertEquals(
			$this->runAjax(),
			array(
				array(
					'id' => 1,
					'name' => 'A channel'
				),
				array(
					'id' => 2,
					'name' => 'Another'
				)
			)
		) ;
	}

}
