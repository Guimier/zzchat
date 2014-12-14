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
	
	/** Get a special entity by its id.
	 * @param int $id The id to look for.
	 */
	protected static function getSpecial( $id )
	{
		if ( $id == -1 )
		{
			return new CliUser() ;
		}
		else
		{
			parent::getSpecial( $id ) ;
		}
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
