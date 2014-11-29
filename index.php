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
		<!-- Meta information -->
		<title></title>
		<meta name="description" content="zz Chat Agora" />
		<meta name="author" content="Guilhem Drogue, Lucien Guimier, Damien Teyssier" />
		<link rel="shortcut icon" type="image/png" href="web/img/favicon.png" />
	
		<!-- Styles -->
		<link rel="stylesheet" media="screen" href="web/css/chat.css" type="text/css" />
	
		<!-- Scripts -->
	
		<script src="web/js/configuration.js"></script>
		<?php sendConfiguration() ; ?>

		<script src="web/lib/jquery.js"></script>
		<script src="web/js/ajax.js"></script>
		<script src="web/js/languages.js"></script>
		<script src="web/js/chat.js"></script>
	</head>
	<body>
        <!--Container-->
        <div id="container">
        	<!--Bandeau-->
            <div id="headband"></div>
            <!--Citations-->
            <div id="quote"></div>
            <!--Annonces-->
            <div id="notices"></div>
            <!--Chat-->
            <div id="chat">
 				<!--Presents-->
                <div id="presents"></div>
            	<!--Messages-->
                <div id="messages"></div>
                <!--WYSIWYG-->
                <div id="wysiwyg"></div>
                <!--Canaux-->
       		    <div id="channels"></div>
            </div>
            <!--Pour le centrage vertical-->
            <div class="strut"></div>
            <!--Formulaire-->
            <div id="login"></div>
            <!--Changeons de langue-->
            <div id="menu"></div>
            <!--Footer-->
            <div id="footer"></div>
      	</div>
	</body>
</html>

