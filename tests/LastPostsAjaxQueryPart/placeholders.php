<?php
// @codeCoverageIgnoreStart

class Channel
{	
	public static function getById( $id ) 
	{
		return new Channel() ; 
	}
	
	public function lastPosts( $from )
	{
		return array( new Post( 1 ), new Post( 2 ) ) ; 
	}
}

class Post
{
	
	public function __construct( $id )
	{
		$this->postId = $id ;
	}
	
	public function getid()
	{
		return $this->postId ;
	}
	
	public function getOwner()
	{
		return new User() ;
	}
	
	public function getDate()
	{
		return 42 + $this->postId ; 
	}
	
	public function getContent()
	{
		return "Bonjour" . $this->postId ;
	}
}

class User
{
	public function getId() { return 18 ; }
	public function getName() { return 'A user' ; }
}
