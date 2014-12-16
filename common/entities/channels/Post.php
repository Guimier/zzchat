<?php

class Post
{
	/*** Instances ***/
	
	private $postId = null ; 
		
	/** The array which contains the data concerning the post. */
	private $postData = null ;
	
	/** Constructor of post instance */
	public function __construct( $id, array $data )
	{
		$this->postId =  $id ;
		$this->postData = $data ;
	}
	
	/** Get the id of this post.
	 * 
	 * @return The Id of the post.
	 * @codeCoverageIgnore Getter
	 */ 
	public function getId()
	{
		return $this->postId ;
	}
	
	/** Get the user who write this post.
	 * 
	 * @return The User who owns the post.
	 * @codeCoverageIgnore Getter
	 */
	public function getOwner()
	{
		return User::getById( $this->postData['owner'] ) ;
	}
	
	/** Get the date of the post.
	 *
	 * @return The date of the post.
	 * @codeCoverageIgnore Getter
	 */ 
	public function getDate()
	{
		return $this->postData['date'] ;
	}
	
	 /** Get the content of the post.
	  * 
	  * @return The content of the Message if it is not hidden or null otherwise.
	  */ 
	public function getContent()
	{
		if ( !$this->postData['hidden'] )
		{
			$ret = $this->postData['content'] ;
		}
		else
		{
			$ret = null ;
		}
		
		return $ret ;
	}
}
