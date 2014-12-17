<?php
// @codeCoverageIgnoreStart

/** Generic child class of AjaxQueryPart. */
class _AjaxQueryPart extends AjaxQueryPart
{
	public function execute() {}
	
	/* Access to protected method getParameter. */
	public function _getParameter( $name, $more )
	{
		return $this->getParameter( $name, $more ) ;
	}
}
