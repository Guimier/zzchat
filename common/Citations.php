<?php

/** Citations manager. */
class Citations
{

	const FILENAME = 'local/citations.json' ;

	private $list ;
	private $edited = false ;

	/** Constructor. */
	public function __construct()
	{
		$this->list = Configuration::getInstance()->loadJson(
			self::FILENAME,
			array()
		) ;
	}

	/** Destructor. */
	public function __destruct()
	{
		if ( $this->edited )
		{
			Configuration::getInstance()->saveJson(
				self::FILENAME,
				$this->list
			) ;
		}
	}

	/** Get a random citation.
	 * Returns false if no citation is available.
	 */
	public function getRandom()
	{
		$last = count( $this->list ) - 1 ;

		if ( $last < 0 )
		{
			$res = false ;
		}
		else
		{
			$selection = rand( 0, $last ) ;
			$res = $this->list[$selection] ;
		}

		return $res ;
	}

	/** Get a citation.
	 * @param number $id Indentifiant of the citation.
	 */
	public function get( $id )
	{
		if ( ! array_key_exists( $id, $this->list ) )
		{
			throw new BadCallException() ;
		}
		
		return $this->list[$id] ;
	}

	/** Get all citations. */
	public function getAll()
	{
		return $this->list ;
	}

	/** Add a citation.
	 * @param string $text The citation.
	 * @param string $author The author of the citation (may be null).
	 */
	public function add( $text, $author )
	{
		$this->list[] = array(
			'text' => $text,
			'author' => $author
		) ;

		$this->edited = true ;
	}

	/** Remove a citation.
	 * @param number $id Identifiant of the citation (key in getAll output).
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

}

