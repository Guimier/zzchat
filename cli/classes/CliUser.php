<?php

/** Command-line user.
 * @codeCoverageIgnore Simple override.
 */
class CliUser extends User
{
	
	public function isActive()
	{
		return true ;
	}
	
	/** Put an user inactive. */
	public function isNowInactive() {}
	
	/** Remember the user is active just now. */
	public function isActiveNow() {}
	
	/** Constructro. */
	public function __construct() {}
	
	/** Get the id of the user.
	 * @return The id of the User instance.
	 */ 
	public function getId()
	{
		return -1 ;
	}
	
	/** Get the name of the user.
	 * @return The name of the User instance.
	 */ 
	public function getName()
	{
		return 'CliUser' ;
	}
	
}
