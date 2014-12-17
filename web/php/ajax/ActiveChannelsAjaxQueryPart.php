<?php

class ActiveChannelsAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		return AjaxFormater::channels(
			Channel::getAllActive()
		) ;
	}
	
}
