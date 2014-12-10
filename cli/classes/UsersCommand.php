<?php

/** Manage users. */
class UsersCommand extends EntityManagementCommand
{
	
	/* Get the entity class name. */
	protected function getEntityClass()
	{
		return 'User' ;
	}

}

