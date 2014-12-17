<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for DateAjaxQueryPart. */
class DateAjaxQueryPartTest extends ClassTester
{

	public function testDate()
	{
		$this->assertEquals(
			$this->runAjax(),
			42
		) ;
	}

}
