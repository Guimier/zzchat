<?php

/** Citation query.
 * Returns a random citation if one exists, false otherwise.
 */
class QuotationAjaxQueryPart extends AjaxQueryPart
{
	
	/** See AjaxQueryPart::execute. */
	public function execute()
	{
		$quotations = new Quotations() ;
		return $quotations->getRandom() ;
	}

}
