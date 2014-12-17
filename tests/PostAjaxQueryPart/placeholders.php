<?php
// @codeCoverageIgnoreStart

class Channel
{	
	public static $calls = array() ;
	
	public static function getById( $id ) 
	{
		return is_int( $id ) ? new Channel() : null ;
	}
	
	public function addPost( $user, $content )
	{
		self::$calls[] = array( 'addPost', 'user' => $user->getId(), 'content' => $content ) ;
	}
}

class User
{ 
	public function getId() { return 18 ; }
	public function getName() { return 'A user' ; }
	public function isActive() { return true ; }
	public function isActiveNow() {}
	public static function getById( $id ) { return new User() ; }
}
