<?php

class LogoutAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		Context::getCanonical()->disconnect() ;
	}
	
}
