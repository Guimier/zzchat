/**
 * @module control
 * @class control
 */
( function ( $ ) {
	
	/**
	 * @method onLanguageChange
	 * @private
	 */
	function onLanguageChange()
	{
		var lang = $( this ).val() ;
		languages.change( lang ) ;
		configuration.setLocal( 'language', lang ) ;
	}

	/**
	 * @method loginSuccess
	 * @private
	 * @todo Load the talk interface
	 * @param {String} data The name, as returned by the server.
	 */
	function loginSuccess( data )
	{
		// We are now logged in as <data>
		$( '#loginForm' ).removeClass( 'login-waiting' ) ;
		$( '#login-error' ).hide() ;
		localStorage.setItem( 'lastname', data ) ;
		initChatPage() ;
	}

	/**
	 * @method loginError
	 * @private
	 * @param {String} errName Error name.
	 * @param {String} message Error message.
	 */
	function loginError( errName, message )
	{
		// We are now logged in as <data>
		$( '#loginForm' ).removeClass( 'login-waiting' ) ;
		$( '#login-error' )
			.text( message )
			.show() ;
	}

	/**
	 * @method onLoginSubmit
	 * @private
	 * @param {Event} evt The jQuery normalised event
	 */
	function onLoginSubmit( evt )
	{
		evt.preventDefault() ;
	
		var username = $( '#pseudo' ).val() ;
		
		$( this ).addClass( 'login-waiting' ) ;
		
		ajax.send(
			'POST',
			'login',
			{ name: username },
			loginSuccess,
			loginError
		) ;
	}

	/*
	 * On batit le formulaire pour la saisie du pseudonyme : method permet de spécifier la méthode utilisée pour passer les paramètres (post ou get), enctype donne le type du paramètre qui sera transmis, onsubmit réaliser une fonction à la soumission (içi vérifier la validité du pseudo).
	 */
	function $createForm()
	{
		return $form = $( '<form>' )
			.attr( 'id', 'loginForm' )
			.submit( onLoginSubmit )
			.append( $( '<label>' )
				.attr( {
					id: 'loginLabel',
					'for': 'pseudo'
				} )
				.trText( "login.welcome" )
			)
			.append( '<br>' )
			.append( $( '<input>' )
				.attr( {
					id: 'pseudo',
					name: 'pseudo',
					type: 'text'
				} )
				.val( localStorage.getItem( 'lastname' ) )
			)
			.append( '<br>' )
			.append( $( '<input>' )
				.attr( {
					id: 'btn',
					type: 'submit'
				} )
				.trAttr("value", "login.validate")
			)
			.append( $( '<p>' )
				.hide()
				.attr( 'id', 'login-error' )
			)
	}

	function $createMenuLang()
	{
		var
			$select = $( '<select>' ),
			$option,
			langList = configuration.get( 'languages' ),
			current = languages.getCurrent() ;
	
		for ( var code in langList )
		{
			$option = $( '<option>' )
				.text( langList[code] )
				.val( code ) ;

			if ( code === current )
			{
				$option.prop( 'selected', true ) ;
			}

			$select.append( $option ) ;
		}
	
		return $( '<form>' )
			.attr( {
				id: 'listLang',
				name: 'listLang'
			} )
			.html( $select
				.attr( {
					id: 'menuint',
					name: 'menuint'
				} )
				.change( onLanguageChange )
			) ;
	}

	function initLoginPage()
	{
		$( '#login' ).html( $createForm() ) ;
	}

	function initChatPage()
	{
		$( 'html' )
			.removeClass( 'page-login' )
			.addClass( 'page-chat' ) ;
	}

	function init()
	{
		$( '#nojs' ).remove() ;
		/*Génération du choix des langues :*/
		$( '#menu' ).html( $createMenuLang() ) ;
		
		if ( configuration.get( 'user' ) === null )
		{
			initLoginPage() ;
		}
		else
		{
			initChatPage() ;
		}
		
		$( '#footer' ).trHtml( "web.footer" ) ;
	}

	/* Run on page load */
	$( init ) ;

} ) ( jQuery ) ;
