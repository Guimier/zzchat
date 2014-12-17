<?php

class WhoamiAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$user = $this->getContext()->getUser() ;
		return $user instanceof User ? AjaxFormater::user( $user ) : null ;
	}
	
}
