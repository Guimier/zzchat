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
		
		$this->getContext()->connect( $name ) ;
		
		return $name ;
	}
	
}
