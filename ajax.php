<?php
/**
 * Entry point for ajax queries.
 * @file
 */

require 'common/init.php' ;

$query = new AjaxQuery( new WebContext( $configuration ) ) ;
$query->execute() ;
$query->show() ;

