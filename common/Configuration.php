<?php
/** The configuration. */
class Configuration
{

	private static $root = null ;

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
	 * @param string $localConfigRelativePath Relative path to the configuration the administrator may change.
	 */
	public static function initiate( $root, $defaultConfigRelativePath, $localConfigRelativePath )
	{
		self::$root = $root ;
		self::$defaultConfig = self::loadJson( $defaultConfigRelativePath, array() ) ;
		self::$localConfig = self::loadJson( $localConfigRelativePath, array() ) ;
	}

	/** Get root repository path. */
	public static function getRootDir()
	{
		return self::$root ;
	}

	/** Get specific data directory path (relative).
	 * @return Full path to a specific data directory.
	 */
	public static function getDataDir( $key )
	{
		return '/local/data/' . $key ;
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

}
