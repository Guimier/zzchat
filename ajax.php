<?php
/**
 * Entry point for ajax queries.
 * @file
 */

require 'common/init.php' ;

$query = new AjaxQuery( Context::getCanonical() ) ;
$query->execute() ;
$query->show() ;

