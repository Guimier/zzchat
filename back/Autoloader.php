<?php

class Autoloader
{

	/*
	 * Root directory for zzChat.
	 */
	private $root ;

	/*
	 * Class list.
	 */
	private $classes ;

	/* Constructor */
	public function __construct(
		/* Root directory of zzchat */
		$root,
		/* Name of the file containing the class list */
		$classList
	)
	{
		$this->root = $root ;
		$this->classes = json_decode(
			file_get_contents( "$root/$classList" ),
			true
		) ;
	}

	/*
	 * Check if we know a class.
	 */
	public function classExists( $className )
	{
		return array_key_exists( $className, $this->classes ) ;
		
	}

	/*
	 * Get path to the file implementing a class (from system root).
	 * Assumes the class exists.
	 */
	public function getClassFullPath( $className )
	{
		return $this->root . '/' . $this->classes[$className] ;
	}

	/*
	 * Load a class if it exists.
	 */
	public function load( $className )
	{
		if ( $this->classExists( $className ) )
		{
			require_once $this->getClassFullPath( $className ) ;
		}
	}

}
