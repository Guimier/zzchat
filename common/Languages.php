<?php

class Languages
{

	/** The only instance. */
	private static $instance = null ;

	/** Get the only instance. */
	public static function getInstance()
	{
		if ( self::$instance === null )
		{
			self::$instance = new Languages() ;
		}
		
		return self::$instance ;
	}

/******************************************************************************/


	/** The known languages. */
	private $known = array( 'en', 'fr' ) ;
	
	/** Lists of messages.
	 * Associative arrays with language codes as keys and associatives array
	 * of messages as values.
	 */
	private $messages = array() ;

	/** do we know about a specific language?
	 * @param string $language Language code.
	 */
	public function languageIsKnown( $language )
	{
		return in_array( $language, $this->known ) ;
	}

	/** Loads a language.
	 * @param string $language Asked language.
	 */
	private function loadLanguage( $language )
	{
		if ( $this->languageIsKnown( $language ) && ! array_key_exists( $language, $this->messages ) )
		{
			$file = Configuration::getInstance()->getRootDir()
				. '/default/languages/' . $language . '.json' ;
			
			$array = file_exists( $file )
				? json_decode( file_get_contents( $file ), true )
				: null ;
			
			if ( $array === null )
			{
				$array = array() ;
			}
			
			$this->messages[$language] = $array ;
		}
	}

	/** Get a raw message in a given message.
	 * @param string $language Asked language.
	 * @param string $key Name of the message.
	 * @return The raw message or null.
	 */
	private function getRawMessageInLanguage( $language, $key )
	{
		$this->loadLanguage( $language ) ;
		
		return array_key_exists( $key, $this->messages[$language] )
			? $this->messages[$language][$key]
			: null ;
	}

	/** Get a raw message.
	 * This method will try to get the one asked by $language, or the one it
	 * founds.
	 * @param string $language Asked language.
	 * @param string $key Name of the message.
	 * @return The raw message or null.
	 */
	private function getRawMessage( $language, $key )
	{
		$raw = null ;

		if ( $language !== null )
		{
			$raw = $this->getRawMessageInLanguage( $language, $key ) ;
		}

		if ( $raw === null )
		{
			$raw = $this->getRawMessageInLanguage( 'en', $key ) ;
		}

		if ( $raw === null )
		{
			$raw = "#[$key]#" ;
		}
		
		return $raw ;
	}

	/** Replace arguments in strings.
	 * @param string $raw The raw message.
	 * @param array $array The arguments.
	 */
	private function replaceArguments( $raw, array $arguments )
	{
		$res = $raw ;
		
		foreach ( $arguments as $key => $value )
		{
			$res = str_replace( '${' . $key . '}', $value, $res ) ;
		}
		
		return $res ;
	}

	/** Get a message.
	 * @param string $language Language for the message.
	 * @param string $key Name of the message.
	 * @param array [$arguments] Arguments that may be included in the
	 *   message. This function will replace ${name} in the message
	 *   with $arguments['name'].
	 */
	public function getMessage( $language, $key, array $arguments = array() )
	{
		$raw = $this->getRawMessage( $language, $key ) ;
		return $this->replaceArguments( $raw, $arguments ) ;
	}
	
	/** Filter an array by prefix.
	 * @param array $array Original array.
	 * @param array $excludes List of prefixes to exclude from the output.
	 */
	private function filter( array $array, array $excludes )
	{
		if ( count( $excludes ) > 0 )
		{
			$res = array() ;
		
			foreach ( $array as $key => $value )
			{
				$i = 0 ;
				while (
					$i < count( $excludes ) &&
					$include = ( strpos( $key, $excludes[$i] ) !== 0 )
				)
				{
					$i++ ;
				}
			
				if ( $include )
				{
					$res[$key] = $value ;
				}
			}
		}
		else
		{
			$res = $array ;
		}
		
		return $res ;
			
	}
	
	/** Get all messages in a language.
	 * @param string $language Language code.
	 * @param boolean $withDefault Insert english messages if not available.
	 * @param array $excludes List of prefixes to exclude from the output.
	 */
	public function getAllMessages( $language, $withDefault, array $excludes = array() )
	{
		$res = array() ;
		
		if ( $this->languageIsKnown( $language ) )
		{
			$this->loadLanguage( $language ) ;
			$res += $this->filter(
				$this->messages[$language],
				$excludes
			) ;
		}
		
		if ( $withDefault )
		{
			$this->loadLanguage( 'en' ) ;
			$res += $this->filter( $this->messages['en'], $excludes ) ;
		}
		
		return $res ;
	}

}
