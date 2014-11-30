<?php

class LoginAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$name = $this->getParameter( 'name', WebContext::BOTH ) ;
		
		if ( $name === null )
		{
			throw new NoNameGivenException() ;
		}
		
		Context::getCanonical()->connect( $name ) ;
		
		return $name ;
	}
	
}
