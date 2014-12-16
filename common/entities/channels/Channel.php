<?php

class Channel extends Entity
{

/***** Class *****/

	/** Put the type of the Entity at channels
	 * @return string The type channels.
	 */
	protected static function getEntityType()
	{
		return 'channels' ;
	}
	
	/** Get a special channel by its id.
	 * @param int $id The id to look for.
	 */
	protected static function getSpecial( $id )
	{
		if ( $id == -1 )
		{
			return new DefaultChannel() ;
		}
		else
		{
			parent::getSpecial( $id ) ;
		}
	}
	
	/** Create a channel.
	 * 
	 * @param string $channelName The name of the channel which is created.
	 * @param string $channelTitle The title of the channel which is created.
	 * @param User $channelCreator The User who has created this channel.
	 * 
	 * @return The Channel instance.
	 */
	public static function create( $channelName, $channelTitle, User $channelCreator, $type )
	{
		return parent::createEntity(
			$channelName,
			array(
				'title' => $channelTitle,
				'creator' => $channelCreator->getId(),
				'type' => $type !== null
					? $type
					: Configuration::getValue( 'channels.defaulttype' ),
				'files' => array(),
				'users' => array(
					$channelCreator->getId() => time()
				)
			)
		) ;
	}
	
	/** Get the file of a posts list by id.
	 * @param int $fileId The id of the file.
	 * 
	 * @return The relative path to the file.
	 */
	private static function getPostsFile( $fileId )
	{
		return Configuration::getDataDir( 'posts' ) . '/' . $fileId . '.json' ;
	}
	
	/** Get the title of the channel.
	 * 
	 * @return The title of the Channel instance.
	 * @codeCoverageIgnore Getter.
	 */
	public function getTitle()
	{
		return $this->getValue( 'title' ) ;
	}
	
	/** Get the type of the channel.
	 * 
	 * @return The type of the Channel instance.
	 * @codeCoverageIgnore Getter.
	 */
	public function getType()
	{
		return $this->getValue( 'type' ) ;
	}
	
	/** Get the creator of the channel.
	 * 
	 * @return The name of the User who has created the channel.
	 * @codeCoverageIgnore Getter.
	 */
	public function getCreator()
	{
		return $this->getValue( 'creator' )  ;
	}
	
	/** Get the id of the file where the next post has to be put.
	* 
	* @return The id of the file we need to insert the new post.
	*/
	public function getInsertFile()
	{
		$postFileId = null ;
		$files = $this->getValue( 'files' ) ;
		$lastIndex = count( $files ) - 1 ;
		if ( $lastIndex >= 0 && ! $this->isFull( $files[$lastIndex] ) )
		{
			$postFileId = $files[$lastIndex] ;
		}
		else
		{
			$postFileId = Configuration::incrementCounter(
				Configuration::getDataDir( 'posts' ) . '/lastid.int'
			) ;
			file_put_contents( self::getPostsFile( $postFileId ) , '[]' ) ;
			$files[] = $postFileId ;
			$this->setValue( 'files', $files ) ;
		}
		
		return $postFileId ;
	}
	
	/** Notify activity by a user on this channel.
	 * Of course, activity by a user means the channel is active.
	 */
	public function activatedBy( User $user )
	{
		$this->isActiveNow() ;
		$this->setArrayValue( 'users', $user->getId(), time() ) ;
	}
	
	/** Add post on the channel.
	 * 
	 * @param User $user The User who adds this post.
	 * @param string $content The content of the post.
	 * 
	 * @return The Post which has been added.
	 */
	public function addPost( User $user, $content )
	{
		$this->activatedBy( $user ) ;
		
		$postLength = strlen( $content ) ;
		$maxlength = Configuration::getValue()->channels.postlength ;		 
		
		if ( $postLength > Configuration::getValue()->channels.postlength )
		{
			throw new PostContentTooLongException( ( ( $postLength - $maxlength ) / $maxlength ) * 100 ) ;
		}
		
		if ( ! is_string( $content ) )
		{
			throw new BadCallException() ;
		}
		
		if ( ! $this->isActive() )
		{
			throw new InactiveChannelException( $this->id ) ;
		}
		
		HTML::checkInput( $content ) ;

		$data = array(
			'owner' => $user->getId(),
			'date' => time(),
			'hidden' => false,
			'content' => $content
		) ;
		
		$postingFileId = $this->getInsertFile() ;
		$postingFile = self::getPostsFile( $postingFileId ) ;
		$posts = Configuration::loadJson( $postingFile ) ;
		$posts[] = $data ;
		Configuration::saveJson( $postingFile, $posts ) ;
	}
	
	/** Check if the current file of the channel is full or not.
	 * 
	 * @param string $fileId The id of the tested file.
	 * 
	 * @return Boolean : true if full, false otherwise.
	 */
	public function isFull( $fileId )
	{
		$fileContent = Configuration::loadJson(
			self::getPostsFile( $fileId )
		) ;
		
		return count( $fileContent ) >= Configuration::getValue( 'channels.filelength' ) ;
	}
	
	/** Get a list of the users on this channel.
	 * @returns A list of User.
	 */
	public function getUsers()
	{
		$users = $this->getValue( 'users' ) ;
		$res = array() ;
		
		foreach ( $users as $id => $lastAction )
		{
			if ( time() - $lastAction <= Configuration::getValue( 'channels.userinactivity' ) )
			{
				$user = User::getById( $id ) ;
				if ( $user->isActive() )
				{
					$res[] = $user ;
				}
			}
		}
		
		return $res ;
	}
	
	/** Get the last posts which have been posted for a while.
	*
	* @param time $beginning
	*
	* @return An array with all the posts posted since the beginning.
	*/
	public function lastPosts( $beginning )
	{
		$lastPosts = array() ;
		
		$files = $this->getValue( 'files') ;
		$currentFile = count( $files ) ;
		
		$end = false ;
		
		while ( ! $end && $currentFile > 0 )
		{
			-- $currentFile ;
			$posts = Configuration::loadJson(
				self::getPostsFile( $files[$currentFile] ),
				array()
			) ;
			
			$currentPost = count( $posts ) ;
			while ( ! $end && $currentPost > 0 )
			{
				-- $currentPost ;
				
				$post = new Post( $currentFile . '-' . $currentPost, $posts[$currentPost] ) ;
				
				if ( $post->getDate() >= $beginning )
				{
					$lastPosts[] = $post ;
				}
				else
				{
					$end = true ;
				}
			}
		}
		
		return array_reverse( $lastPosts ) ;
	}
	
}

