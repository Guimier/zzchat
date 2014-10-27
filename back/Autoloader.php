<?php

class Autoloader {

	/*
	 * Root directory for zzChat.
	 */
	private $root;

	/*
	 * Class list.
	 */
	private $classes;

	public function __construct( $root, $classList ) {
		$this->root = $root;
		$this->classes = json_decode(
			file_get_contents( "$root/$classList" ),
			true
		);
	}

	public function load( $className ) {
		if ( array_key_exists( $className, $this->classes ) ) {
			require_once $this->root
				. '/'
				. $this->classes[$className];
		}
	}

}
