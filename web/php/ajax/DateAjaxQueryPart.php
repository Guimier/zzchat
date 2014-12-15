<?php

class DateAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		return time() ;
	}
	
}
