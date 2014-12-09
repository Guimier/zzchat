<?php

class User Extends Entity
{
	
/***** Class *****/

	/** Put the type of the Entity at users
	 * @return string The type users. 
	 */ 
	protected static function getEntityType()
	{
		return 'users' ;	
	}
	
	public function isActive()
	{
		return parent::isActive() && ! $this->getValue( 'loged-out' ) ;
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
		return parent::createEntity(
			$userName,
			array(
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
}
