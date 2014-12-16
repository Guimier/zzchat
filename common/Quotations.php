<?php

/** Quotations manager. */
class Quotations
{

	const FILENAME = 'local/quotations.json' ;

	private $list ;
	private $edited = false ;

	/** Constructor. */
	public function __construct()
	{
		$this->list = Configuration::loadJson(
			self::FILENAME,
			array()
		) ;
	}

	/** Destructor. */
	public function __destruct()
	{
		if ( $this->edited )
		{
			Configuration::saveJson(
				self::FILENAME,
				$this->list
			) ;
		}
	}

	/** Get a random quotation.
	 * Returns null if no quotation is available.
	 */
	public function getRandom()
	{
		$last = count( $this->list ) - 1 ;

		if ( $last < 0 )
		{
			$res = null ;
		}
		else
		{
			$selection = rand( 0, $last ) ;
			$res = $this->list[$selection] ;
		}

		return $res ;
	}

	/** Get a quotation.
	 * @param number $id Indentifiant of the quotation.
	 */
	public function get( $id )
	{
		if ( ! array_key_exists( $id, $this->list ) )
		{
			throw new BadCallException() ;
		}
		
		return $this->list[$id] ;
	}

	/** Get all quotations. */
	public function getAll()
	{
		return $this->list ;
	}

	/** Add a quotation.
	 * @param string $text The quotation.
	 * @param string $author The author of the quotation (may be null).
	 */
	public function add( $text, $author )
	{
		$this->list[] = array(
			'text' => $text,
			'author' => $author
		) ;

		$this->edited = true ;
	}

	/** Remove a quotation.
	 * @param number $id Identifiant of the quotation (key in getAll output).
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

