<?php

class WhoamiAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = Context::getCanonical()->getUser() ;
		return $user instanceof User ? $user->getName() : null ;
	}
	
}
