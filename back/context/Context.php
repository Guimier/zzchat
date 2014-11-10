<?php
/** The context of execution.
 * Includes configuration (static context) and parameters (dynamic context).
 */
abstract class Context
{

	/** Constructor.
	 * @param string $root Full path to the root directory of Agora.
	 */
	public function __construct( $root )
	{
		/* Path */
		$this->root = $root ;
		/* Configuration */
		$this->loadConfiguration();
	}

/***** Path *****/

	/** Root directory path. */
	private $root = null;

	/** Get root repository path. */
	public function getRootDir()
	{
		return $this->root ;
	}

	/** Get specific data directory path.
	 * @return Full path to a specific data directory.
	 */
	public function getDataDir( $key )
	{
		return $this->getRootDir() . '/data/' . $key ;
	}

/***** Configuration *****/

	/** Configuration.
	 * The initial values are the default ones; they will be overridden on construction by
	 * any key in /config/global.json whose type matches the one given here.
	 */
	private $configuration = array(
		/* Maximum allowed number of open channels */
		'channels.maxnum' => 100,
		/* Default style of channels */
		'channels.style' => 'theater',
		/* Maximum inactivity for a channel */
		'channels.inactivity' => 604800,
		/* Maximum length of a post (bytes) */
		'channels.postlength' => 1024,
		/* Number of posts stored in one file */
		'channels.filelength' => 100,
		/* Maximum post rate allowed for a user (post/minute) */
		'user.maxpostrate' => 20,
		/* Default language */
		'user.defaultlang' => 'en',
		/* Maximum inactivity for a user (sec) */
		'user.inactivity' => 86400 
	) ;
	
	/** Merge an array into another, selecting only keys with matching types.
	 *
	 * @param array $src Source array.
	 * @param array &$dest Destination array.
	 */
	private function mergeTypedArrays( $src, &$dest )
	{
		foreach ( $src as $key => $val )
		{
			if ( array_key_exists( $key, $dest ) && gettype( $val ) == gettype( $dest[$key] ) )
			{
				$dest[$key] = $val ;
			}
		}
		
	}

	/** Load configuration
	 * This method read /config/gloabl.json and override values when types match.
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
	
	/** Get specific configuration value.
	 * @throws NoSuchConfigurationKeyException Thrown if the value does not exist.
	 */
	public function getConf( $key )
	{
		if ( ! array_key_exists( $key, $this->configuration ) )
		{
			throw new NoSuchConfigurationKeyException( $key ) ;
		}
		
		return $this->configuration[$key] ;
	}

/***** Parameters *****/

	/** Get a parameter of the call.
	 * 
	 * @param string $key Name of the parameter.
	 * @param $more May be used by subclasses for selection precision (for example GET/POST).
	 * @return Value of the parameter.
	 */
	abstract function getParameter( $key, $more ) ;

}
