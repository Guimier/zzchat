<?php
/**
 * Entry point for ajax queries.
 * @file
 */

require 'back/init.php' ;

$context = new WebContext( __DIR__, $_POST, $_GET ) ;
$query = new AjaxQuery( $context ) ;
$query->execute() ;
$query->show() ;

