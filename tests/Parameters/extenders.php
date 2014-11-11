<?php

/** Generic child class of Parameters.
 * @codeCoverageIgnore
 */
class _Parameters extends Parameters
{

	public function getValue( $key, $more = null )
	{
		switch ( $key )
		{
			case 'foo' : return 'bar' ;
			case 'empty' : return '' ;
			case 'list' : return 'foo,bar,baz' ;
			case 'null' : return null ;
		}
	}

}
