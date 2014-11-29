<?php

class Users
{
	
/***** Class *****/
	/** Get an active user by name.
	 * 
	 * @param string $name The name to look for.
	 * 
	 * @return The User instance or null.
	 */
	public static function getActiveUser( $name )
	{
		$user = null ;
		
		$config = Configuration::getInstance() ;
		
		$activeUsers = $config->loadJson( $config->getDataDir( 'users' ) . '/active.json', array() ) ;
		
		if ( array_key_exists( $name, $activeUser ) )
		{
			$user = self::getUser( $activeUsers[$name] ) ;
		}
		
		if ( ! $user->isActive() )
		{
			$user = null ;
		}
		
		return $user ;
	}
	
	/** Put an user inactive
	 *  
	 * @throw UserAlreadyInactiveException If the user is already inactive. 
	 */ 
		
	public function isNowInactive()
	{
		if ( ! $this->isActive )
		{
			throw new UserAlreadyInactiveException( $this->id ) ;
		}
		else
		{
			$config = Configuration::getInstance() ;
			$activeUsers = loadJson( $config->getDataDir( 'users' ) . '/active.json', array() ) ;
			
			unset ( $activeUsers[$this->userData['name']] ) ;
			
			saveJson( $config->getDataDir( 'users' ) . '/active.json', $activeUsers) ;
		}
	}
	
	/** 
	*
	*
	*
	*/
	public function isActiveNow()
	{
		$this->userData['last-action'] = time() ;
	}
	
	/** Get a user by id.
	 * 
	 * @param int $userId The id to look for.
	 * 
	 * @return The User instance.
	 */
	
	public static function getUser( $userId )
	{
		static $users = array() ;
		
		if ( ! array_key_exists( $userId, $users ) )
		{
			$users[$userId] = new User( $userId ) ; 
		}
		
		return $users[$userId] ; 
	}
	
	
	
	/** Create a user.
	 * 
	 * @param string $userName The name of the user which is created.
	 * 
	 * @throw UserNameAlreadyTakenException If the name is already in use.
	 * 
	 * @return The User instance.
	 */
	public static function createUser( $userName )
	{
		$config = Configuration::getInstance() ; 
		
		if ( self::getActiveUser( $userName ) !== null )
		{
			throw new UserNameAlreadyTakenException( $userName ) ;
		}
		
		$lastidFile = $config->getDataDir( 'users' ) . '/lastid.int' ;
		$id = $config->incrementCounter( $lastIdFile ) ;
		
		$config->saveJson(
			self::getUserFile( $id ),
			array(
				'name' => $userName,
				'last-action' => time()				
			)
		) ;
		
		return self::getUser( $id ) ;
	}
	
	/** Get the file of the user by id.
	 * @param int $userId The id of the user whose file is searched.  
	 * 
	 * @return The File of the user.
	 */
	private static function getUserFile( $userId )
	{
		return Configuration::getInstance()->getDataDir( 'users' ) . '/' . $userId . '.json' ;
	}
	
	
/**** Instances ****/
	
	/** The id of the user. */
	private $id = -1 ;
	
	/** The array which contains the data concerning the user. */
	private $userData = null ;
	
	/** Constructor
	 * 
	 * @param int $userId The id of the User which is built.
	 * 
	 * @throw NoSuchUserException If there is no user whith this id.
	 */
	public function __construct( $userId )  
	{	
		$this->id = $userId ;
		$raw = file_get_contents( $this->getUserFile( $userId ) ) ;
		
		if ( $raw === null ) // err
		{
			throw new NoSuchUserException( $userId ) ;
		}
		else // charger données
		{
			$this->userData = json_decode( $raw, true ) ;
		}
	}
	
	/** Check whether the user is active or not.
	 * 
	 * @return True if the user is active, false otherwise. 
	 */
	public function isActive()
	{
		$config = Configuration::getInstance() ;
		$activeUsers = $config->loadJson( $config->getDataDir( 'users' ) . '/active.json', array() ) ;
		return time() - $this->userData['last-action'] < Configuration::getInstance()->getValue( 'user.inactivity' ) 
				&& array_key_exists( $this->userData['name'], $activeUsers ) ;
	
	}
	
	/** Get the id of the user.
	 * 
	 * @return The id of the User instance.
	 * @codeCoverageIgnore
	 */ 
	public function getId()
	{
		return $this->id ;
	}
	
	
	/** Get the name of the user.
	 * 
	 * @return The name of the User instance.
	 * @codeCoverageIgnore
	 */ 
	public function getName()
	{
		return $this->userData['name'] ;
	}
	
}
