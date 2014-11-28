<?php

class Channel
{
	/** Get an active channel by name.
	 * 
	 * @param string $name The name to look for.
	 * 
	 * @return The Channel instance or null.
	 */
	public static function getActiveChannel( $name )
	{
		$channel = null ;
		
		$config = Configuration::getInstance() ;
		
		$activeChannels = $config->loadJson( $config->getDataDir( 'channels' ) . '/active.json', array() ) ;
		
		if ( array_key_exists( $name, $activeChannel ) )
		{
			$channel = self::getChannel( $activeChannel[$name] ) ;
		}
		
		if ( ! $channel->isActive() )
		{
			$channel = null ;
		}
		
		return $channel ;
	}
	
	
	
	
	/** Get a channel by id.
	 * 
	 * @param int $channelId The id to look for.
	 * 
	 * @return The Channel instance.
	 */
	
	public static function getChannel( $channelId )
	{
		static $channels = array() ;
		
		if ( ! array_key_exists( $channelId, $channels ) )
		{
			$channels[$channelId] = new User( $channelId ) ; 
		}
		
		return $channels[$channelId] ; 
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
		$config = Configuration::getInstance() ; 
		
		if ( self::getActiveChannel( $channelName ) !== null )
		{
			throw new ChannelNameAlreadyTakenException( $channelName ) ;
		}
		
		$lastidFile = $config->getDataDir( 'channels' ) . '/lastid.int' ;
		$id = $config->incrementCounter( $lastIdFile ) ;
		
		$config->saveJson(
			self::getChannelFile( $id ),
			array(
				'name' => $channelName,
				'title' => $channelTitle,
				'creator' => $channelCreator->getId(),
				'type' => $type,
				'creation' => time(),
				'last-action' => time(),
				'files' => array()				
			)
		) ;
		
		return self::getChannel( $id ) ;
	}
	
	
	
	/** Get the file of the channel by id.
	 * @param int $channelId The id of the channel whose file is searched.  
	 * 
	 * @return The File of the channel.
	 */
	private static function getChannelFile( $userId )
	{
		return Configuration::getInstance()->getDataDir( 'channels' ) . '/' . $channelId . '.json' ;
	}
	
	private static function getPostsFile( $fileId ) 
	{
		return  $config->getDataDir( 'posts' ) . '/' . $FileId . '.json' ;
	}
	
	
	/***Instances***/
	
	/** The id of the channel */
	private $id = -1 ;
	
	/** The array which contains the data concerning the channel. */
	private $channelData = null ;
	
	/** Constructor of channel instance */
	public function __construct( $channelId )
	{
		$this->id = $channelId ;
		$raw = file_get_contents( $this->getChannelFile( $channelId ) ) ;
		
		if ( $raw === null ) // err
		{
			throw new NoSuchChannelException( $userId ) ;
		}
		else // charger données
		{
			$this->channelData = json_decode( $raw, true ) ;
		}
	}
	
	/** Check whether the channel is active or not.
	 * 
	 * @return True if the channel is active, false otherwise. 
	 */
	public function isActive()
	{
		return time() - $this->channelData['last-action'] < Configuration::getInstance()->getValue( 'channel.inactivity' ) ;
	}
	
	/** Get the id of the channel.
	 * 
	 * @return The id of the Channel instance.
	 * @codeCoverageIgnore
	 */ 
	public function getId()
	{
		return $this->id ;
	}
	
	/** Get the name of the channel.
	 * 
	 * @return The name of the Channel instance.
	 * @codeCoverageIgnore
	 */ 
	public function getName()
	{
		return $this->channelData['name'] ;
	}
	
	/** Get the title of the channel.
	 * 
	 * @return The title of the Channel instance.
	 * @codeCoverageIgnore
	 */ 
	public function getTitle()
	{
		return $this->channelData['title'] ;
	}
	
	/** Get the creator of the channel.
	 * 
	 * @return The name of the User who has created the channel.
	 * @codeCoverageIgnore
	 */
	public function getCreator()
	{
		return $this->channelData['creator'] ;
	}
	
	public function getInsertFile()
	{
		$postFileId = null ;
		$counter = count( $this->files ) ;
		if ( $counter !== 0 && ! $this->isFull( $this->files[$counter] ) )
		{
			$postFileId = $this->files[$counter] ;
		}
		else
		{
			$postFileId = Configuration::getInstance()->getCounter( Configuration::getInstance()->getDataDir( 'posts' ) . '/lastid.int' ) ;
			file_put_contents( self::getPostsFile( $postFileId ) , '[]' ) ;
			$this->files[] = $postFileId ;
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
	public function addPost( $user, $content )
	{
		$config = Configuration::getInstance() ;
		
		$data = array(
			'owner' => $user,
			'date' => time(),
			'hidden' => false,
			'content' => $content			 
		) ;
		
		$postingFileId = $this->getInsertFile() ;
		$postingFile = self::getPostsFile( $postingFile ) ;
		
		file_put_contents( $postingFile, $config->loadJson(  ) ) ;
				
	}
		
	/** Check if the current file of the channel is full or not.
	 * 
	 * @param File $file The file which is tested.
	 * 
	 * @return Boolean : true if full, false otherwise.
	 */ 
	public function isFull( $file )
	{
		$config = Configuration::getInstance() ;
		$fileContent = $config->loadJson( $file ) ;
		 
		return count( $fileContent ) >= $config->getValue( 'channel.maxnum' ) ; 
	}
}
