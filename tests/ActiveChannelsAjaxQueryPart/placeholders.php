<?php
// @codeCoverageIgnoreStart

class Channel
{
	public static function getAllActive() {
		return array(
			new Channel( 1 ),
			new Channel( 2 )
		) ;
	}

	private $id;
	public function __construct( $id ) { $this->id = (int) $id ; }
	
	public function getId() { return $this->id ; }
	public function getName() { return $this->id === 1 ? 'A channel' : 'Another' ; }
	public function getType() { return 'normal' ; }
	public function getTitle() { return 'Some string' ; }
	public function activatedBy() { self::$calls[] = array( 'activatedBy' ) ; }
	public function getUsers() { return array( new User() ) ; }
}
