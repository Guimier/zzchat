<?php
// @codeCoverageIgnoreStart

class User {
	public static function getById( $id ) { return new User() ; }
	public function isActive() { return true ; }
	public function isActiveNow() {}
	public function isNowInactive() {}
}
