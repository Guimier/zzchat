<?php
/**
 * Entry point for ajax queries.
 * @file
 */

require 'back/init.php' ;

$query = new AjaxQuery( new WebContext( $configuration ) ) ;
$query->execute() ;
$query->show() ;

