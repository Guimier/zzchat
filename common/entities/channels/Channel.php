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

	
	
	/** Create a channel.
	 * 
	 * @param string $channelName The name of the channel which is created.
	 * @param string $channelTitle The title of the channel which is created.
	 * @param User $channelCreator The User who has created this channel.
	 * 
	 * @return The Channel instance.
	 */
	public static function createChannel( $channelName, $channelTitle, User $channelCreator, $type )
	{
		return parent::createEntity(
			$channelName,
			array(
				'title' => $channelTitle,
				'creator' => $channelCreator->getId(),
				'type' => $type !== null
					? $type
					: $config->getValue( 'channels.defaulttype' ),
				'creation' => time(),
				'last-action' => time(),
				'files' => array()
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
		return Configuration::getInstance()->getDataDir( 'posts' ) . '/' . $fileId . '.json' ;
	}
		
	/** Get the title of the channel.
	 * 
	 * @return The title of the Channel instance.
	 * @codeCoverageIgnore
	 */
	public function getTitle()
	{
		return $this->getValue( 'title' ) ;
	}
	
	/** Get the creator of the channel.
	 * 
	 * @return The name of the User who has created the channel.
	 * @codeCoverageIgnore
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
			$config = Configuration::getInstance() ;
			$postFileId = $config->incrementCounter( 'lastpostfile' ) ;
			file_put_contents( self::getPostsFile( $postFileId ) , '[]' ) ;
			$files[] = $postFileId ;
			$this->setValue( 'files', $files ) ;
		}
		
		return $postFileId ;
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
		if ( ! is_string( $content ) )
		{
			throw new BadCallException() ;
		}
		
		if ( ! $this->isActive() )
		{
			throw new InactiveChannelException( $this->id ) ;
		}
		
		HTML::checkInput( $content ) ;

		$config = Configuration::getInstance() ;
		
		$data = array(
			'owner' => $user->getId(),
			'date' => time(),
			'hidden' => false,
			'content' => $content
		) ;
		
		$postingFileId = $this->getInsertFile() ;
		$postingFile = self::getPostsFile( $postingFileId ) ;
		$posts = $config->loadJson( $postingFile ) ;
		$posts[] = $data ;
		$config->saveJson( $postingFile, $posts ) ;
	}
		
	/** Check if the current file of the channel is full or not.
	 * 
	 * @param string $fileId The id of the tested file.
	 * 
	 * @return Boolean : true if full, false otherwise.
	 */
	public function isFull( $fileId )
	{
		$config = Configuration::getInstance() ;
		$fileContent = $config->loadJson( self::getPostsFile( $fileId ) ) ;
		
		return count( $fileContent ) >= $config->getValue( 'channels.filelength' ) ;
	}
	
	/** Get the last posts which have been posted for a while.
	*
	* @param time $beginning
	*
	* @return An array with all the posts posted since the beginning.
	*/
	public function lastPosts( $beginning )
	{
		$config = Configuration::getInstance() ;
		$files = $this->getValue( 'files') ;
		$lenght = count( $files ) ;
		$currentFile = $lenght ;
		$postsFile = $config->loadJson( $files[$currentFile], array() ) ;
		$currentPost = count( $postsFile ) ;
		while ( $postsFile[$currentPost]['date'] >= $begining  && $currentFile > 0 )
		{
			if ( $currentPost > 0 )
			{
				$currentPost = $currentPost - 1 ;
			}
			else
			{
				$currentFile = $currentFile - 1 ;
				$postsFile = $config->loadJson( $files[$currentFile], array() ) ;
				$currentPost = count( $postsFile ) ;
			}
		}
		
		$lastPosts = array() ;
		
		if ( $postsFile[$currentPost]['date'] < $beginning )
		{
			if ( $currentPosts  = count( $postsFile ) )
			{
				$currentFile = $currentFile + 1 ;
				$currentPost = 0 ;
			}
			else
			{
				$currentPost = $currentPost + 1 ;
			}
		}
		
		while ( $currentFile < $lenght || $currentPost < count( $config->loadJson( $files[$lenght], array() ) ) )
		{
			if ( $currentPost < count( $currentFile ) )
			{
				$lastPosts[] = $postsFile[$currentPost] ;
				$currentPost = $currentPost + 1 ;
			}
			else
			{
				$lastPosts[] = $postsFile[$currentPost] ;
				$currentFile = $currentFile + 1 ;
				$postsFile = $config->loadJson( $files[$currentFile], array() ) ;
				$currentPost = 0 ;
			}
		}
	
		return $lastPosts ;
	}	
	
}

