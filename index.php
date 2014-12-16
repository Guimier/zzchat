<?php

require_once __DIR__ . '/common/init.php' ;
header( 'Content-Type: text/html; charset=UTF-8' ) ;

/** Build a call to “javacript:configuration.initialise()” with server configuration. */
function sendConfiguration()
{
	echo '<script>' ;
	
	$langs = Languages::getInstance() ;
	$user = Context::getCanonical()->getUser() ;
	
	$jsConf = array(
		'languages' => $langs->getAllLanguages(),
		'language' => Configuration::getValue( 'language' ),
		'postsrate' => Configuration::getValue( 'ajax.postsrate' ),
		'metarate' => Configuration::getValue( 'ajax.metarate' ),
		'channelsrate' => Configuration::getValue( 'ajax.channelsrate' ),
		'backlog' => Configuration::getValue( 'ajax.backlog' ),
		'defaulttype' => Configuration::getValue( 'channels.defaulttype' ),
		'user' => $user instanceof User ? $user->getName() : null,
		'channels' => array( -1 )
	) ;
	
	echo 'configuration.initialise( ' . JSON::encode( $jsConf ) . ' ) ;' ;
	
	echo "</script>\n" ;
}

/** Insert the notice if it has been defined. */
function showNotice()
{
	$noticeFile = Configuration::getFullPath( 'local/notice.html' ) ;
	
	if ( file_exists( $noticeFile ) )
	{
		echo '<div id="notices">' . file_get_contents( $noticeFile ) . "</div>" ;
	}
	
	echo "\n" ;
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
			href="web/lib/jquery-ui.css" 
		/>
		<link 
			rel="stylesheet" media="screen" type="text/css"
			href="web/lib/morrigan.css" 
		/>
		<link
			rel="stylesheet" media="screen" type="text/css"
			href="web/css/morrigan.css" 
		/>
		<link 
			rel="stylesheet" media="screen" type="text/css"
			href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" 
		/>
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
		<script src="web/lib/jquery.js"></script>
		<script src="web/lib/jquery-ui.js"></script>
		<script src="web/js/configuration.js"></script>
		<?php sendConfiguration() ; ?>
		<script src="web/js/ajax.js"></script>
		<script src="web/js/languages.js"></script>
		<script src="web/js/morrigan.js"></script>
		<script src="web/lib/morrigan.js"></script>
		<script src="web/js/channel.js"></script>
		<script src="web/js/chat.js"></script>
	</head>
	<body>
		<!--Container-->
		<div id="container">
			<!--Bandeau-->
			<div id="headband">
				<div id="agora"></div>
				<!--Citations-->
				<div id="quoteBlock">
					<div id="commands">
						<span id="hello"></span>
						<input id="disconnect" type="button">
						<!--Changeons de langue-->
						<select id="menuint"></select>
					</div>
					<div id="quote"></div>
					<div id="author"></div>
				</div>
			</div>
			<div id="container2">
				<?php showNotice() ; ?>
				<!--Chat-->
				<div id="chat">
					<!--Canaux-->
					<div id="channels-list">
						<div id="channels-inactives">
							<input id="channels-new" type="button">
							<div id="channels-title"></div>
							<ul id="channels-list-inactives"></ul>
						</div>
						<ul id="channels-list-actives"></ul>
					</div>
					<div id="channels"></div>
				</div>
			</div>
			<!--Pour le centrage vertical-->
			<div class="strut"></div>
			<!--Formulaire-->
			<div id="login">
				<span id="nojs" class="error"><?php echo Context::getCanonical()->getMessage( 'web.nojs' ) ; ?></span>
			</div>
			<!--Footer-->
			<div id="footer"></div>
		</div>
	</body>
</html>

