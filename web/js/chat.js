/**
 * @module control
 */
( function ( $ ) {
	
	/**
	 * @method onLanguageChange
	 */
	function onLanguageChange()
	{
		var lang = $( this ).val() ;
		languages.change( lang ) ;
		configuration.setLocal( 'language', lang ) ;
	}

	function onLoginSubmit( evt )
	{
		evt.preventDefault() ;
	
		 // TODO
	}

	/**
	 * On batit le formulaire pour la saisie du pseudonyme : method permet de spécifier la méthode utilisée pour passer les paramètres (post ou get), enctype donne le type du paramètre qui sera transmis, onsubmit réaliser une fonction à la soumission (içi vérifier la validité du pseudo).
	 */
	function $createForm()
	{
		return $form = $( '<form>' )
			.attr( 'id', 'myForm' )
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
			)
			.append( '<br>' )
			.append( $( '<input>' )
				.attr( {
					id: 'btn',
					type: 'button'
				} )
				.trAttr("value", "login.validate")
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

	function init()
	{
		$( '#nojs' ).remove() ;
		/*Génération du choix des langues :*/
		$( '#menu' ).html( $createMenuLang() ) ;
		/*Génération : on remplace ce qui est désigné par l'ID dans le html par ce qui suit
		Appel de $createForm pour batir le formulaire de login.*/
		$( '#login' ).html( $createForm() ) ;
		$( '#footer' ).trHtml( "web.footer" ) ;
	}

	/* Run on page load */
	$( init ) ;

} ) ( jQuery ) ;
