<?php

/** Classes autoloader.
 * Enables autoloading of classes defined in classes.json.
 *
 * May be called directly or defined as autoloader using:
 * @code{.php}
 *     spl_autoload_register( array( $autoloader, 'load' ) );
 * @endcode
 */
class Autoloader
{

	/** Root directory for zzChat. */
	private $root ;

	/** List of known classes extracted from classes.json. */
	private $classes ;

	/** Constructor
	 *
	 * @param string $root Root directory of Agora.
	 * @param string $classList Name of the file containing the class list.
	 */
	public function __construct( $root, $classList )
	{
		$this->root = $root ;
		$this->classes = json_decode(
			file_get_contents( "$root/$classList" ),
			true
		) ;
	}

	/** Check if a class is known
	 *
	 * @param string $className Name of the class to check.
	 */
	public function classExists( $className )
	{
		return array_key_exists( $className, $this->classes ) ;
		
	}

	/** Get relative path to the file implementing a class
	 * @warning Assumes the class exists.
	 * @param string $className Name of the class whose path is wanted.
	 * @return Path to the file where the class is defined, relative to the root directory of Agora.
	 */
	public function getClassFullPath( $className )
	{
		return $this->root . '/' . $this->classes[$className] ;
	}

	/** Load a class if it exists.
	 * @warning This function is not unit-tested since require_once is not easy to test.
	 * @param string $className Name of the class to load.
	 * @codeCoverageIgnore
	 */
	public function load( $className )
	{
		if ( $this->classExists( $className ) )
		{
			require_once $this->getClassFullPath( $className ) ;
		}
	}

}
