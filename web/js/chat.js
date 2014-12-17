/**
 * @module control
 * @class control
 * @static
 */
( function ( $ ) {

'use strict' ;
	
	var
		/**
		 * Current user
		 * @property {String} user
		 * @private
		 */
		user = configuration.get( 'user' ); 
	
	/**
	 * @method onLanguageChange
	 * @private
	 */
	function onLanguageChange()
	{
		// jshint validthis: true
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
	function loginSuccess( name )
	{
		user = name ;
		localStorage.setItem( 'lastname', name ) ;
		$( '#loginForm' ).removeClass( 'login-waiting' ) ;
		$( '#login-error' ).hide() ;
		initChatPage() ;
	}

	/**
	 * @method loginError
	 * @private
	 * @param {AjaxError} err Error.
	 */
	function loginError( err )
	{
		console.log( err ) ;
		$( '#loginForm' ).removeClass( 'login-waiting' ) ;
		$( '#login-error' )
			.trText( err.msgName, err.msgArgs )
			.show() ;
	}

	function gotQuotation( desc )
	{
		if ( desc !== null )
		{
			$( '#quote' ).trText( 'quotations.quote', { content: desc.text } ) ;

			$( '#author' ).trText(
				desc.author === null ? 'quotations.anonymous' : 'quotations.author',
				{ name: desc.author }
			) ;
		}
	}

	function newQuotation()
	{
		ajax.add(
			'GET',
			'quotation',
			null,
			gotQuotation
		) ;
	}

	/**
	 * @method onLoginSubmit
	 * @private
	 * @param {Event} evt The jQuery normalised event
	 */
	function onLoginSubmit( evt )
	{
		// jshint validthis: true
		evt.preventDefault() ;
	
		var username = $( '#pseudo' ).val() ;
		
		$( this ).addClass( 'login-waiting' ) ;

		newQuotation() ;
		ajax.send(
			'POST',
			'login',
			{ name: username },
			loginSuccess,
			loginError
		) ;
	}

	/*
	 * On batit le formulaire pour la saisie du pseudonyme.
	 */
	function $createForm()
	{
		return $( '<form>' )
			.attr( 'id', 'loginForm' )
			.submit( onLoginSubmit )
			.append( $( '<label>' )
				.attr( {
					id: 'loginLabel',
					'for': 'pseudo'
				} )
				.trText( 'login.welcome' )
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
				.trAttr( 'value', 'login.validate' )
			)
			.append( $( '<p>' )
				.hide()
				.attr( 'id', 'login-error' )
				.addClass( 'error' )
			) ;
	}

	function createMenuLang()
	{
		var
			$select = $( '#menuint' ),
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
	
		$select
			.attr( {
				id: 'menuint',
				name: 'menuint'
			} )
			.change( onLanguageChange ) ;
	}

	function logout()
	{
		ajax.stop() ;
		ajax.send( 'POST', 'logout' ) ;
		user = null ;
		initLoginPage() ;
	}

	function initLoginPage()
	{
		$( '#login' ).html( $createForm() ) ;
		channels.stop() ;
		$( 'html' )
			.addClass( 'page-login' )
			.removeClass( 'page-chat' ) ;
	}

	function initChatPage()
	{
		channels.start() ;
		$( 'html' )
			.removeClass( 'page-login' )
			.addClass( 'page-chat' ) ;
		$( '#hello' ).trText( 'menu.hello', { user: user } ) ;
	}

	function init()
	{
		$( '#nojs' ).remove() ;
		$( '#disconnect' ).click( logout ) ;
		/*Génération du choix des langues :*/
		createMenuLang() ;
		$( '#disconnect' ).trAttr( 'value', 'menu.logout' ) ;
		
		if ( configuration.get( 'user' ) === null )
		{
			initLoginPage() ;
		}
		else
		{
			newQuotation() ;
			initChatPage() ;
		}
		
		$( '#footer' ).trHtml( 'web.footer' ) ;
	}

	/* Run on page load */
	$( init ) ;

} ) ( jQuery ) ;
