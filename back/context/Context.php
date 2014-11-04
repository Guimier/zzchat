<?php
/*
 * Context object.
 */

abstract class Context
{

/***** Constructor *****/

	public function __construct()
	{
		/* Path */
		$this->root = dirname( __DIR__ ) ;
		/* Configuration */
		$this->loadConfiguration();
	}

/***** Path *****/

	/* Root directory path */
	private $root = null;

	/* Get root repository path */
	public function getRootDir()
	{
		return $this->root ;
	}

	/* Get specific data directory path */
	public function getDataDir( $key )
	{
		return $this->root . '/data/' . $key ;
	}

/***** Configuration *****/

	/*
	 * Default configuration, will be overridden on construction.
	 * Types need to match.
	 */
	private $configuration = array(
		/* Maximum allowed number of open channels */
		'channels.maxnum' => 100,
		/* Default style of channels */
		'channels.style' => 'theater',
		/* Maximum post rate allowed for a user (post/minute) */
		'user.maxpostrate' => 20,
		/* Maximum length of a post (bytes) */
		'channels.postlength' => 1024,
		/* Default language */
		'user.defaultlang' => 'en',
		/* Number of posts stored in one file */
		'channels.filelength' => 100,
		/* Maximum inactivity for a user (sec) */
		'user.inactivity' => 86400,
		/* Maximum inactivity for a channel */
		'channels.inactivity' => 604800 
	) ;
	
	/*
	 * Merge an array into another, selecting only keys with matching types.
	 */
	private function mergeTypedArrays( $src, &$dest )
	{
		foreach ( $src as $key => $val )
		{
			if ( gettype( $val ) == gettype( $dest[$src] ) )
			{
				$dest[$key] = $val ;
			}
		}
		
	}

	/*
	 * Load configuration (override defaults with stored values).
	 */
	private function loadConfiguration()
	{
		$raw = file_get_contents(
			$this->root . '/config/global.json'
		) ;

		if ( $raw !== null )
		{
			$arr = json_decode( $raw, true ) ;

			if ( $arr !== null )
			{
				$this->mergeTypedArrays(
					$arr,
					$this->configuration
				) ;
			}
		}
	}
	
	/*
	 * Get specific configuration value.
	 * Throws NoSuchConfigurationKeyException if the value does not exist.
	 */
	public function getConf( $key )
	{
		if ( ! array_key_exists( $key, $this->configuration ) )
		{
			throw new NoSuchConfigurationKeyException( $key ) ;
		}
		
		return $this->configuration[$key] ;
	}

}
