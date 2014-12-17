<?php
// @codeCoverageIgnoreStart

class User
{
	public function getId() { return 18 ; }
	public function getName() { return 'A user' ; }
	
}

class Channel
{
	public static $calls = array() ;
	
	public static function getById( $id )
	{
		self::$calls[] = array( 'getById', $id );
		return new Channel( $id ) ;
	}
	
	public static function getByName( $name )
	{
		self::$calls[] = array( 'getByName', $name );
		return new Channel( $name === 'A channel' ? 1 : 2 ) ;
	}
	
/******************************************************************************/
	
	private $id;
	public function __construct( $id ) { $this->id = (int) $id ; }
	
	public function getId() { return $this->id ; }
	public function getName() { return $this->id === 1 ? 'A channel' : 'Another' ; }
	public function getType() { return 'normal' ; }
	public function getTitle() { return 'Some string' ; }
	public function activatedBy() { self::$calls[] = array( 'activatedBy' ) ; }
	public function getUsers() { return array( new User() ) ; }
	
}
