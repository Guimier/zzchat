<?php

/** Manage users. */
class UsersCommand extends EntityManagementCommand
{
	/** See Command::getDocumentation. */
	public function getDocumentation()
	{
		return $this->buildDocumentation( array(
			'description' => 'cli.users',
			'scenarios' => array(
				'eject' => 'cli.users.eject',
				'show' => 'cli.users.show'
			)
		) ) ;
	}


	/** See EntityManagementCommand::getEntityClass. */
	protected function getEntityClass()
	{
		return 'User' ;
	}

}

