<?php

class DateAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		return $this->getContext()->getTime() ;
	}
	
}
