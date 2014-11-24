<?php

class Post
{
	/*** Instances ***/
		
	/** The array which contains the data concerning the post. */
	private $postData = null ;
	
	/** Constructor of post instance */
	public function __construct( array $data )
	{
		$this->postData = $data ;
	}
	
	/** Get the user who write this post.
	 * 
	 * @return The owner of the post.
	 * @codeCoverageIgnore
	 */
	public function getOwner()
	{
		return this->postData['owner'] ;
	}
	
	/** Get the date of the post.
	 *
	 * @return The date of the post.
	 * @codeCoverageIgnore
	 */ 
	public function getDate()
	{
		return this->postData['date'] ;
	}
	
	 /** Get the content of the post.
	  * 
	  * @return The content of the Message if it is not hidden or null otherwise.
	  */ 
	public function getContent()
	{
		if (!postData['hidden'])
		{
			$ret = this->postData['content'] ;
		}
		else
		{
			$ret = null ;
		}
		
		return $ret ;
	}
}
