<?php
/** The configuration. */
class Configuration
{

	/** The only instance. */
	private static $instance = null ;

	/** Get the only instance. */
	public static function getInstance()
	{
		if ( self::$instance === null )
		{
			/* Exception since AgoraException depends on Configuration. */
			return new Exception( 'No instance of Configuration.' ) ;
		}
		
		return self::$instance ;
	}

	/** Set the only instance.
	 * @param string $root Full path to the root directory of Agora.
	 * @param string $defaultConfig Relative path to the default configuration.
	 * @param string $adminConfig Relative path to the configuration the administrator may change.
	 */
	public static function setInstance( $root, $defaultConfig, $adminConfig )
	{
		if ( self::$instance !== null )
		{
			/* Exception since AgoraException depends on Languages. */
			return new Exception( 'Double instanciation of Configuration.' ) ;
		}
		
		self::$instance = new Configuration( $root, $defaultConfig, $adminConfig ) ;
		
		return self::$instance ;
	}

/******************************************************************************/

	/** Constructor.
	 * @param string $root Full path to the root directory of Agora.
	 * @param string $defaultConfig Relative path to the default configuration.
	 * @param string $adminConfig Relative path to the configuration the administrator may change.
	 */
	private function __construct( $root, $defaultConfig, $adminConfig )
	{
		/* Path */
		$this->root = $root ;
		/* Configuration */
		$this->loadConfiguration( $defaultConfig, $adminConfig );
	}

/***** Path *****/

	/** Root directory path. */
	private $root = null;

	/** Get root repository path. */
	public function getRootDir()
	{
		return $this->root ;
	}

	/** Get specific data directory path (relative).
	 * @return Full path to a specific data directory.
	 */
	public function getDataDir( $key )
	{
		return '/data/' . $key ;
	}

/***** File reading/writing *****/

	/** Get the parsed JSON content of a file.
	 * @param string $file Relative path to the file.
	 * @param [$default=null] Default value if the file does not exist or contains invalid JSON.
	 */
	public function loadJson( $file, $default = null )
	{
		$res = $default ;

		$full = $this->getRootDir() . '/' . $file ;

		if ( file_exists( $full ) )
		{
			$parsed = json_decode(
				file_get_contents( $full ),
				true
			);

			if ( $parsed !== null )
			{
				$res = $parsed ;
			}
		}

		return $res ;
	}

	/** Save inot a JSON file a value.
	 * @param string $file Relative path to the file.
	 * @param $value The value to save.
	 */
	public function saveJson( $file, $value )
	{
		file_put_contents(
			$full = $this->getRootDir() . '/' . $file,
			json_encode( $value )
		) ;
	}

/***** Configuration *****/

	/** Configuration.
	 * The initial values are the default ones; they will be overridden on construction by
	 * any key in /config/global.json whose type matches the one given here.
	 */
	private $configuration = array() ;
	
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

	/* Unserialize a JSON file.
	 * @param string $file Relative path to the JSON file.
	 */
	private function loadJson( $file )
	{
		$full = $this->root . '/' . $file ;
		$raw = null ;
		if ( file_exists( $full ) )
		{
			$raw = file_get_contents( $full ) ;
		}
		return ( $raw !== null ) ? json_decode( $raw, true ) : null ;
	}

	/** Load configuration
	 * This method read /config/gloabl.json and override values when types match.
	 * @param string $defaultConfig Relative path to the default configuration.
	 * @param string $adminConfig Relative path to the configuration the administrator may change.
	 */
	private function loadConfiguration( $defaultConfig, $adminConfig )
	{
		$this->configuration = $this->loadJson( $defaultConfig ) ;

		$changes = $this->loadJson( $adminConfig ) ;

		if ( $changes !== null )
		{
			$this->mergeTypedArrays(
				$changes,
				$this->configuration
			) ;
		}
	}
	
	/** Get specific configuration value.
	 * @throws NoSuchConfigurationKeyException Thrown if the value does not exist.
	 */
	public function getValue( $key )
	{
		if ( ! array_key_exists( $key, $this->configuration ) )
		{
			throw new NoSuchConfigurationKeyException( $key ) ;
		}
		
		return $this->configuration[$key] ;
	}

}
