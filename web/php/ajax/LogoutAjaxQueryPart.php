<?php

class LogoutAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$this->getContext()->disconnect() ;
	}
	
}
