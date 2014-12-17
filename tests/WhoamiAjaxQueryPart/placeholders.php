<?php
// @codeCoverageIgnoreStart

class User
{
	public function getId() { return 18 ; }
	public function getName() { return 'A user' ; }
	public function isActive() { return true ; }
	public function isActiveNow() {}
	public static function getById( $id ) { return new User() ; }
}
