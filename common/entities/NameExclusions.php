<?php

class NameExclusions
{
	const FILENAME = 'nameexclusions.json' ;
	
	private $list ;
	private $edited = false ;

	/** Constructor. */
	public function __construct()
	{
		$this->list = Configuration::loadJson(
			Configuration::getLocalDir() . '/' . self::FILENAME,
			array()
		) ;
	}

	/** Destructor. */
	public function __destruct()
	{
		if ( $this->edited )
		{
			Configuration::saveJson(
				Configuration::getLocalDir() . '/' . self::FILENAME,
				$this->list
			) ;
		}
	}

	/** Get a name exclusion.
	 * @param number $id Indentifiant of the name exclusion.
	 */
	public function get( $id )
	{
		if ( ! array_key_exists( $id, $this->list ) )
		{
			throw new BadCallException() ;
		}
		
		return $this->list[$id] ;
	}

	/** Get all name exclusions. */
	public function getAll()
	{
		return $this->list ;
	}

	/** Add a name exclusion.
	 * @param string $text The name exclusion.
	 */
	public function add( $name )
	{
		$this->list[] = $name ;

		$this->edited = true ;
	}

	/** Remove a name exclusion.
	 * @param number $id Identifiant of the name exclusion (key in getAll output).
	 */
	public function remove( $id )
	{
		if ( ! array_key_exists( $id, $this->list ) )
		{
			throw new BadCallException() ;
		}
		
		unset( $this->list[$id] ) ;
		$this->list = array_values( $this->list );
		$this->edited = true ;
	}
	
	public function checkName( $name )
	{
		foreach( $this->list as $pattern )
		{
			if ( preg_match( '#' . str_replace( '#', '\#', $pattern ) . '#', $name ) )
			{
				throw new NotAuthorizedNameException( $name ) ;
			}
		}
	}
}
