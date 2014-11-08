<?php

ClassTester::load( 'NoSuchConfigurationKeyException' ) ;

/** Generic child class of Context
 * @codeCoverageIgnore
 */
class _Context extends Context
{
	public function getParameter( $key, $more ) {}
}

