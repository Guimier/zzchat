<?php

class LoginAjaxQueryPart extends AjaxQueryPart
{
	
	public function execute()
	{
		$name = $this->getParameter( 'name', WebContext::POST ) ;
		
		if ( $name === null )
		{
			throw new WebMissingParameterException( 'name' ) ;
		}
		
		$this->getContext()->connect( $name ) ;
		
		return $name ;
	}
	
}
