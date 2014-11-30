<?php

class User
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
		
		if ( array_key_exists( $name, $activeUsers ) )
		{
			$user = self::getUser( $activeUsers[$name]->getId() ) ;

			if ( ! $user->isActive() )
			{
				$user = null ;
			}
		}
		
		return $user ;
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
		
		if ( strlen( $userName ) < $config->getValue( 'user.minnamelength' ) )
		{
			throw new UserNameTooShortException( $userName ) ;
		}
		
		if ( self::getActiveUser( $userName ) !== null )
		{
			throw new UserNameAlreadyTakenException( $userName ) ;
		}
		
		$id = $config->incrementCounter( 'lastuser' ) ;
		
		$config->saveJson(
			self::getUserFile( $id ),
			array(
				'name' => $userName,
				'last-action' => time(),
				'logged-out' => false
			)
		) ;
		
		$activeUsersFile = $config->getDataDir( 'users' ) . '/active.json' ;
		$activeUsers = $config->loadJson( $activeUsersFile, array() ) ;
		
		$activeUsers[$userName] = $id ;
		$config->saveJson( $activeUsersFile, $activeUsers ) ;
		
		
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
	
	
/***** Instances *****/
	
	/** The id of the user. */
	private $id = -1 ;
	
	/** The array which contains the data concerning the user. */
	private $userData = null ;
	
	/** The data have been edited */
	private $modified = false ;
	
	/** Constructor.
	 * 
	 * @param int $userId The id of the User which is built.
	 * 
	 * @throw NoSuchUserException If there is no user whith this id.
	 */
	public function __construct( $userId )  
	{
		$this->id = $userId ;
		$this->userData = Configuration::getInstance()->loadJson(
			$this->getUserFile( $userId )
		) ;
		
		if ( $this->userData === null )
		{
			throw new NoSuchUserException( $userId ) ;
		}
	}
	
	/** Destructor.
	 * Save the data if modified.
	 */
	public function __destruct()  
	{
		if ( $this->modified )
		{
			Configuration::getInstance()->saveJson(
				$this->getUserFile( $this->id ),
				$this->userData
			) ;
		}
	}
	
	/** Change a key in the data array.
	 * @param string $key The key to change.
	 * @param mixed $value The new value.
	 */
	private function setValue( $key, $value )
	{
		$this->userData[$key] = $value ;
		$this->modified = true ;
	}
	
	/** Check whether the user is active or not.
	 * 
	 * @return True if the user is active, false otherwise. 
	 */
	public function isActive()
	{
		$config = Configuration::getInstance() ;
		return time() - $this->userData['last-action'] < $config->getValue( 'user.inactivity' ) 
			&& ! $this->userData['loged-out'] ;
	
	}
	
	/** Put an user inactive
	 *  
	 * @throw UserAlreadyInactiveException If the user is already inactive. 
	 */
	public function isNowInactive()
	{
		if ( ! $this->isActive() )
		{
			throw new UserAlreadyInactiveException( $this->id ) ;
		}
		else
		{
			$this->setValue( 'logged-out', true ) ;
			
			$config = Configuration::getInstance() ;
			$activeUsers = $config->loadJson( $config->getDataDir( 'users' ) . '/active.json', array() ) ;
			
			unset ( $activeUsers[$this->userData['name']] ) ;
			
			$config->saveJson( $config->getDataDir( 'users' ) . '/active.json', $activeUsers) ;
		}
	}
	
	/** Remember the user is active just now. */
	public function isActiveNow()
	{
		$this->setValue( 'last-action', time() ) ;
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
