<?php

/** Manage users.
 * @codeCoverageIgnore 
 */
class ChannelsCommand extends EntityManagementCommand
{

	/** See EntityManagementCommand::getEntityClass. */
	protected function getEntityClass()
	{
		return 'Channel' ;
	}

}
