<?php
// @codeCoverageIgnoreStart

class AgoraInternalException extends Exception {
	
	private $msg ;
	private $args ;

	public function __construct( $msg, $args )
	{
		$this->msg = $msg ;
		$this->args = $args ;
	}
	
	public function getMsg() { return $this->msg ; }
	public function getArgs() { return $this->args ; }

}
