<?php
/*
 * Context object.
 */

class Context
{
	
	private $root = null;

	/*
	 * Default configuration, will be overridden on __construct.
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
		/* Number of posts storred in one file */
		'channels.filelength' => 100,
		/* Maximum inactivity for a user (sec) */
		'user.inactivity' => 86400,
		/* Maximum inactivity for a channel */
		'channels.inactivity' => 604800 
	) ;

	public function __construct()
	{
		$this->root = dirname( __DIR__ ) ;

		$this->loadConfiguration();
	}

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

	private function loadConfiguration()
	{
		$raw = file_get_contents(
			$this->root . '/config.global.json'
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

	public function getRootDir()
	{
		return $this->root ;
	}

	public function getDataDir( $key )
	{
		return $this->root . '/data/' . $key ;
	}

	public function getConf( $key )
	{
		if ( array_key_exists( $key, $this->configuration ) )
		{
			return $this->configuration[$key] ;
		}
		else
		{
			return null:
		}
	}

}
