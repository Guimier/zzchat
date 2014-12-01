<?php

require_once __DIR__ . '/common/init.php' ;
header( 'Content-Type: text/html; charset=UTF-8' ) ;

function sendConfiguration()
{
	echo '<script>' ;
	
	$config = Configuration::getInstance() ;
	$langs = Languages::getInstance() ;
	$user = Context::getCanonical()->getUser() ;
	
	$jsConf = array(
		'languages' => $langs->getAllLanguages(),
		'language' => $config->getValue( 'user.defaultlang' ),
		'newpostsrate' => $config->getValue( 'ajaxrate.newposts' ),
		'peoplerate' => $config->getValue( 'ajaxrate.people' ),
		'user' => $user instanceof User ? $user->getName() : null
	) ;
	
	echo 'configuration.initialise( ' . JSON::encode( $jsConf ) . ' ) ;' ;
	
	echo '</script>' ;
}

?><!DOCTYPE html>
<html class="page-login">
	<head>
		<!-- Meta information -->
		<title></title>
		<meta name="description" content="zz Chat Agora" />
		<meta name="author" content="Guilhem Drogue, Lucien Guimier, Damien Teyssier" />
		<link rel="shortcut icon" type="image/png" href="web/img/favicon.png" />
	
		<!-- Styles -->
		<link
			rel="stylesheet" media="screen" type="text/css"
			id="css-common"
			href="web/css/common.css"
		/>
		<link
			rel="stylesheet" media="screen" type="text/css"
			id="css-login"
			href="web/css/login.css"
		/>
		<link
			rel="stylesheet" media="screen" type="text/css"
			id="css-chat"
			href="web/css/chat.css"
		/>
	
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
            <div id="login">
            	<span id="nojs"><?php echo Context::getCanonical()->getMessage( 'web.nojs' ) ; ?></span>
            </div>
            <!--Changeons de langue-->
            <div id="menu"></div>
            <!--Footer-->
            <div id="footer"></div>
      	</div>
	</body>
</html>

