<?php

/** Citation query.
 * Returns a random citation if one exists, false otherwise.
 */
class CitationAjaxQueryPart extends AjaxQueryPart
{
	
	/** See AjaxQueryPart::execute. */
	public function execute()
	{
		return ( new Citations() )->getRandom() ;
	}

}
