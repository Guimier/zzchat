<?php

/** Manage users. */
class UsersCommand extends EntityManagementCommand
{

	/** See EntityManagementCommand::getEntityClass. */
	protected function getEntityClass()
	{
		return 'User' ;
	}

}

