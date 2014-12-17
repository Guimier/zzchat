<?php
// @codeCoverageIgnoreStart

class Quotations
{
	public static $calls = array() ;
	
	public function add( $text, $author = null )
	{
		self::$calls[] = array( 'text' => $text, 'author' => $author ) ;
	}
}
