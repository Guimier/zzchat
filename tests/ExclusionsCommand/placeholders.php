<?php
// @codeCoverageIgnoreStart

class NameExclusions
{
	public static $calls = array() ;
	
	public function add( $text )
	{
		self::$calls[] = array( 'add', 'text' => $text ) ;
	}
	
	public function remove( $id )
	{
		self::$calls[] = array( 'remove', 'id' => $id ) ;
	}
	
	public function getAll()
	{
		return array( 'Rodolf', 'a+b' ) ;
	}
	
	public function get( $id )
	{
		self::$calls[] = array( 'get', 'id' => $id ) ;
		return 'Rodolf' ;
	}
}
