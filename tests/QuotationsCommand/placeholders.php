<?php
// @codeCoverageIgnoreStart

class Quotations
{
	public static $calls = array() ;
	
	public function add( $text, $author = null )
	{
		self::$calls[] = array( 'add', 'text' => $text, 'author' => $author ) ;
	}
	
	public function remove( $id )
	{
		self::$calls[] = array( 'remove', 'id' => $id ) ;
	}
	
	public function getAll()
	{
		return array(
			array( 'text' => 'I am', 'author' => 'Me' ),
			array( 'text' => 'You are', 'author' => null )
		) ;
	}
	
	public function get( $id )
	{
		self::$calls[] = array( 'get', 'id' => $id ) ;
		return array( 'text' => 'I am', 'author' => 'Me' ) ;
	}
}
