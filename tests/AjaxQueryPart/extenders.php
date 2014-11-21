<?php

/** Generic child class of AjaxQueryPart
 * @codeCoverageIgnore
 */
class _AjaxQueryPart extends AjaxQueryPart
{
	public function execute() {}
	
	/* Access to protected method getParameter. */
	public function _getParameter( $name, $more )
	{
		return $this->getParameter( $name, $more ) ;
	}
}
