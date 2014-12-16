<?php
/** The configuration. */
class Configuration
{

	/** Get the only instance.
	 * @deprecated Use this as a static class instead.
	 */
	public static function getInstance()
	{
		return new Configuration() ;
	}

	/** Initiate the configuration.
	 * @param string $root Full path to the root directory of Agora.
	 * @param string $defaultConfigRelativePath Relative path to the default configuration.
	 * @param string $localDir Relative path to the directory containing local data.
	 */
	public static function initiate( $root, $localDir )
	{
		self::$root = $root ;
		self::$localDir = $localDir ;
		self::$defaultConfig = self::loadJson( "default/configuration.json", array() ) ;
		self::$localConfig = self::loadJson( "$localDir/configuration.json", array() ) ;
	}

/***** Paths *****/

	/** Full path to the root directory of Agora. */
	private static $root = null ;
	
	/** Relative path to the directory containing local data. */
	private static $localDir = null ;
	
	/** Get root repository path. */
	public static function getRootDir()
	{
		return self::$root ;
	}

	/** Get specific data directory path (relative).
	 * @param string $key Key for a subdirectory.
	 * @return Full path to a specific data directory.
	 */
	public static function getDataDir( $key )
	{
		return self::$localDir . '/data/' . $key ;
	}

	/** Get specific local data relative path.
	 * @return Full path to a specific data directory.
	 * @codeCoverageIgnore Getter.
	 */
	public static function getLocalDir()
	{
		return self::$localDir ;
	}

	/** Get the full path to a file.
	 * @param string $relative Relative path to the file.
	 * @return $full Full path to the file.
	 */
	public static function getFullPath( $relative )
	{
		return self::getRootDir() . '/' . $relative ;
	}

/***** File reading/writing *****/

	/** Get the parsed JSON content of a file.
	 * @param string $file Relative path to the file.
	 * @param [$default=null] Default value if the file does not exist or contains invalid JSON.
	 */
	public static function loadJson( $file, $default = null )
	{
		$res = $default ;

		$full = self::getFullPath( $file ) ;

		if ( file_exists( $full ) )
		{
			$parsed = JSON::decode( file_get_contents( $full ) );

			if ( $parsed !== null )
			{
				$res = $parsed ;
			}
		}

		return $res ;
	}

	/** Save a value into a JSON file.
	 * @param string $file Relative path to the file.
	 * @param $value The value to save.
	 */
	public static function saveJson( $file, $value )
	{
		$full = self::getFullPath( $file ) ;
		$setRights = ! file_exists( $full ) ;

		file_put_contents( $full, JSON::encode( $value ) ) ;

		if ( $setRights )
		{
			chmod( $full, 0666 ) ;
		}
	}

	/** Get the value of a counter and increment it.
	 * @param string $counter Relative path to the counter file.
	 * @return The saved value.
	 */
	public static function incrementCounter( $counter )
	{
		$full = self::getFullPath( $counter ) ;
		$value = 1 + (int) file_get_contents( $full ) ;
		file_put_contents( $full, $value ) ;
		return $value ;
	}

/***** Configuration *****/

	/** Default configuration. */
	private static $defaultConfig = array() ;

	/** Local configuration. */
	private static $localConfig = array() ;
	
	/** Get specific configuration value.
	 * @throws NoSuchConfigurationKeyException Thrown if the value does not exist.
	 */
	public static function getValue( $key )
	{
		if ( ! array_key_exists( $key, self::$defaultConfig ) )
		{
			throw new NoSuchConfigurationKeyException( $key ) ;
		}
		
		$res = self::$defaultConfig[$key] ;
		
		if ( array_key_exists( $key, self::$localConfig ) && gettype( self::$localConfig[$key] ) === gettype( $res ) )
		{
			$res = self::$localConfig[$key] ;
		}
		
		return $res ;
	}

/***** Configuration management. *****/

	/** Get the state for a key.
	 *
	 * @param string $key The key whose state is wanted.
	 *
	 * @return An array fille with:
	 *   * `default`: default value, as in `default/configuration.json`.
	 *   * `local`: local value (only if defined), as in `local/configuration.json`.
	 *   * `real`: value is use (i.e. the one returned by getValue).
	 */
	public static function getState( $key )
	{
		if ( ! array_key_exists( $key, self::$defaultConfig ) )
		{
			throw new NoSuchConfigurationKeyException( $key ) ;
		}
		
		$res = array(
			'default' => self::$defaultConfig[$key],
			'real' => self::getValue( $key )
		) ;
			
		if ( array_key_exists( $key, self::$localConfig ) )
		{
			$res['local'] = self::$localConfig[$key] ;
		}
		
		return $res ;
	}

	/** Get all configuration keys.
	 * @return An array indexed by configuration keys. Values are array, as returned by getState.
	 */
	public static function getGlobalState()
	{
		$res = array() ;
		$keys = array_keys( self::$defaultConfig ) ;
		
		foreach ( $keys as $key )
		{
			$res[$key] = self::getState( $key ) ;
		}
		
		return $res ;
	}

	/** Save the edited local configuration. */
	public static function saveLocal()
	{
		self::saveJson( self::$localDir . '/configuration.json', self::$localConfig ) ;
	}

	/** Return to default for a given key.
	 * @param string $key The key to delete from local configuration.
	 */
	public static function returnToDefault( $key )
	{
		unset( self::$localConfig[$key] ) ;
	}

	/** Chanve the value fo a given key.
	 * @param string $key The key to change.
	 * @param mixed $value The value to set.
	 */
	public static function setValue( $key, $value )
	{
		if ( ! array_key_exists( $key, self::$defaultConfig ) )
		{
			throw new NoSuchConfigurationKeyException( $key ) ;
		}
		
		if ( gettype( $value ) !== gettype( self::$defaultConfig[$key] ) )
		{
			throw new InvalidConfigurationValueException(
				$key,
				gettype( self::$defaultConfig[$key] ),
				gettype( $value )
			) ;
		}
		
		self::$localConfig[$key] = $value ;
	}
	
	/** Get a translated message in the default language.
	 * @param string $key The name of the message.
	 * @param array $args The arguments.
	 */
	public function getMessage( $key, array $args  =array() )
	{
		return Languages::getInstance()->getMessage(
			self::getValue( 'language' ),
			$key, $args
		) ;
	}

}
