<?php

require_once __DIR__ . '/common/init.php' ;
header( 'Content-Type: text/html; charset=UTF-8' ) ;

function sendConfiguration()
{
	echo '<script>' ;
	
	$config = Configuration::getInstance() ;
	$langs = Languages::getInstance() ;
	
	$jsConf = array(
		'languages' => $langs->getAllLanguages(),
		'language' => $config->getValue( 'user.defaultlang' ),
		'newpostsrate' => $config->getValue( 'ajaxrate.newposts' ),
		'peoplerate' => $config->getValue( 'ajaxrate.people' )
	) ;
	
	echo 'configuration.initialise( ' . JSON::encode( $jsConf ) . ' ) ;' ;
	
	echo '</script>' ;
}

?><!DOCTYPE html>
<html>
<head>
	<title>Empty page</title>
	<script src="web/js/configuration.js"></script>
	<?php sendConfiguration() ; ?>

	<script src="web/lib/jquery.js"></script>
	<script src="web/js/ajax.js"></script>
	<script src="web/js/languages.js"></script>
</head>
<body>
</body>
</html>

