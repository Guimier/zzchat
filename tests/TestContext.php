<?php
// @codeCoverageIgnoreStart

class TestContext extends Context
{
	public function __construct() {}
	public function getParameter( $key, $more = null ) { return null ; }
	public function getUser() { return null ; }
	public function getTime() { return 42 ; } // We just need a fixed value here, why not?
}
