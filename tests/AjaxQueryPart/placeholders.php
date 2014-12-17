<?php
// @codeCoverageIgnoreStart

/** Placeholder for WebContext. */
class WebContext
{
	function getParameter( $key, $more )
	{
		switch ( $key )
		{
			case 'foo_bar': return 'baz' ; break ;
			default: return '_default_' ;
		}
	}
}

