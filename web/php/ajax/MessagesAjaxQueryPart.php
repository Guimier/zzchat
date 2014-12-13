<?php

class MessagesAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$language = $this->getParameter( 'language' ) ;
		$withDefaults = $this->getBooleanParameter( 'defaults' ) ;
		
		return Languages::getInstance()->getAllMessages(
			$language,
			$withDefaults,
			array( 'cli.', 'configuration.' )
		) ;
	}
	
}
