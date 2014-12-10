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
		return parent::isActive() && ! $this->getValue( 'logged-out' ) ;
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
		
		$activeUsersFile = Configuration::getDataDir( 'users' ) . '/active.json' ;
		$activeUsers = Configuration::loadJson( $activeUsersFile, array() ) ;
		
		$activeUsers[$userName] = $id ;
		Configuration::saveJson( $activeUsersFile, $activeUsers ) ;
		
		
		return self::getUser( $id ) ;
	}

	public function isNowInactive()
	{
		parent::isNowInactive() ;
		$this->setValue( 'logged-out', true ) ;
	}
}
