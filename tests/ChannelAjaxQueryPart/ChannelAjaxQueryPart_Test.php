<?php
// @codeCoverageIgnoreStart

require_once dirname( __DIR__ ) . '/ClassTester.php' ;

/** Test for  ChannelAjaxQueryPart. */
class ChannelAjaxQueryPart_Test extends ClassTester
{

	/** @expectedException WebMissingParameterException */
	public function testWithoutId()
	{
		$this->assertEquals(
			$this->runAjax( array() ),
			42
		) ;
	}

	public function testOneId()
	{
		require_once __DIR__ . '/placeholders.php' ;
		$get = array(
			'p_id' => '1'
		) ;
	
		$this->assertEquals(
			$this->runAjax( $get ),
			array(
				1 => array(
					'id' => 1,
					'name' => 'A channel',
					'title' => 'Some string',
					'type' => 'normal',
					'users' => array(
						array(
							'id' => 18,
							'name' => 'A user'
						)
					)
				)
			)
		) ;
	}

	public function testMultipleIds()
	{
		require_once __DIR__ . '/placeholders.php' ;
		$get = array(
			'p_id' => '1,2'
		) ;
	
		$this->assertEquals(
			$this->runAjax( $get ),
			array(
				1 => array(
					'id' => 1,
					'name' => 'A channel',
					'title' => 'Some string',
					'type' => 'normal',
					'users' => array(
						array(
							'id' => 18,
							'name' => 'A user'
						)
					)
				),
				2 => array(
					'id' => 2,
					'name' => 'Another',
					'title' => 'Some string',
					'type' => 'normal',
					'users' => array(
						array(
							'id' => 18,
							'name' => 'A user'
						)
					)
				)
			)
		) ;
	}

	public function testOneName()
	{
		require_once __DIR__ . '/placeholders.php' ;
		$get = array(
			'p_name' => 'A channel'
		) ;
	
		$this->assertEquals(
			$this->runAjax( $get ),
			array(
				1 => array(
					'id' => 1,
					'name' => 'A channel',
					'title' => 'Some string',
					'type' => 'normal',
					'users' => array(
						array(
							'id' => 18,
							'name' => 'A user'
						)
					)
				)
			)
		) ;
	}

}
