<?php

class User extends Entity
{
	
/***** Class *****/

	/** Put the type of the Entity at users
	 * @return string The type users.
	 */ 
	protected static function getEntityType()
	{
		return 'users' ;
	}
	
	/** Create a user.
	 * 
	 * @param string $userName The name of the user which is created.
	 * 
	 * @throw UserNameAlreadyTakenException If the name is already in use.
	 * 
	 * @return The User instance.
	 */
	public static function create( $userName )
	{
		return parent::createEntity(
			$userName,
			array(
				'last-action' => time(),
				'logged-out' => false
			)
		) ;
	}
	
	public function isActive()
	{
		return parent::isActive() && ! $this->getValue( 'logged-out' ) ;
	}

	public function isNowInactive()
	{
		parent::isNowInactive() ;
		$this->setValue( 'logged-out', true ) ;
	}
}
