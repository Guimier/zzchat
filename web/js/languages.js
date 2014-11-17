/** Language abstraction.
 * @class languages
 */
( function ( $ ) {

	var
		/** Current language code.
		 * @private
		 * @property {String} current
		 */
		current = 'en',
		
		/** Messages.
		 * Associative array, keys are languages codes.
		 * Values are messages lists (associative arrays keyed by message names).
		 * @private
		 * @property {Objetc} messages
		 */
		messages = {},
		
		/** Waiters for the messages. Keyed by the language codes. */
		waiters = {},
		
		/** jQuery native $.fn.attr */
		core_attr = $.fn.attr,
		/** jQuery native $.fn.text */
		core_text = $.fn.text,
		/** jQuery native $.fn.html */
		core_html = $.fn.html ;

	window.languages = {} ;
	
	/** Get the current language.
	 * @method getCurrent
	 */
	window.languages.getCurrent = function ()
	{
		return current ;
	} ;
	
	function getStringValue( instanceRepresentation )
	{
		var
			key,
			args = instanceRepresentation.arguments,
			res = messages[current][instanceRepresentation.message] ;
		
		for ( key in args )
		{
			res = res.replace( '${' + $key + '}', args[key] ) ;
		}
		
		return res ;
	}
	
	/** Send the query for a language.
	 * @method sendLanguageQuery
	 * @private
	 * @param {String} language Code of the language to load.
	 * @param {Function} callback Callback called when the language is loaded.
	 *   First argument will be the language array.
	 */
	function sendLanguageQuery( language, callback )
	{
		ajax.add(
			'GET',
			'messages',
			{
				language: language,
				defaults: true
			},
			function ( data )
			{
				messages[language] = data ;
				callback( data ) ;
			}
		) ;
		
		if ( ! ajax.isAutomaticOn() )
		{
			ajax.sendNow() ;
		}
	}
	
	/** Load a language.
	 * @method loadLanguage
	 * @private
	 * @param {String} language Code of the language to load.
	 * @param {Function} callback Callback called when the language is loaded.
	 *  First argument will be the language array.
	 */
	function loadLanguage( language, callback )
	{
		if ( $.isArray( messages[language] ) )
		{
			callback( messages[language] ) ;
		}
		else
		{
			if ( ! $.isArray( waiters[language] ) )
			{
				waiters[language] = [ callback ] ;
				
				sendLanguageQuery(
					language,
					function ( data )
					{
						var i, callbacks = waiters[language] ;
						delete waiters[language] ;
						for ( i in waiters )
						{
							waiters[i]( data ) ;
						}
					}
				) ;
			}
			else
			{
				waiters.push( callback ) ;
			}
		}
	}

	/** jQuery Extension for languages.
	 * @class jQuery.language
	 * @see http:s//api.jquery.com for the native jQuery methods.
	 */
	
	/** Get translation data for a node.
	 * @method getTranslationData
	 * @private
	 * @param {jQuery} $node The node whose data are wanted.
	 */
	function getTranslationData( $node )
	{
		var translationData ;
		
		console.log( arguments ) ;
		
		try
		{
			translationData = JSON.parse( $node.attr( 'data-translation' ) ) ;
		}
		catch ( e )
		{
			translationData = {} ;
		}
		
		return translationData ;
	}
	
	/** Set translation data for a node.
	 * @method setTranslationData
	 * @private
	 * @param {jQuery} $node The node whose data are wanted.
	 * @param {array} data The data to be attached to the node.
	 */
	function setTranslationData( $node, data )
	{
		$node.attr( 'data-translation', JSON.stringify( data ) ) ;
	}
	
	/** Delete a translation if needed.
	 * @method deleteTranslation
	 * @private
	 * @param {jQuery} $node The node whose data needs to be deleted.
	 * @param {String} key Key of the data to delete.
	 */
	function deleteTranslation( $node, key )
	{
		var data = getTranslationData( $node ) ;
		delete data[key] ;
		setTranslationData( $node, data ) ;
	}
	
	/* jQuery extension. */
	$.extend( $.fn, {
		
		/** Set or change a translatable attribute.
		 * @method trAttr
		 * @param {String} attrName The attribute to change.
		 * @param {String} msgId The id of the message to use.
		 * @param {Object} [args] Arguments of the message.
		 */
		trAttr: function ( attrName, msgId, args )
		{
			var
				$this = $( this ),
				data = getTranslationData( $( this ) ) ;
			
			data[attrName] = {
				'message': msgId,
				'arguments': args
			} ;
			
			setTranslationData( $this, data ) ;
			
			loadLanguage(
				current,
				function ( data )
				{
					core_attr.call(
						$this,
						attrName,
						getStringValue( data[attrName] )
					) ;
				}
			) ;
			
			return this ;
		},
		
		/** Wrapper for $.fn.attr. */
		attr: function ( attr, value )
		{
			if ( value !== undefined )
			{
				deleteTranslation( $( this ), attr ) ;
			}
			
			return core_attr.apply( this, arguments ) ;
		},
		
		/** Set or change a translatable content (escaped).
		 * @method trText
		 * @param {String} msgId The id of the message to use.
		 * @param {Object} args Arguments of the message.
		 */
		trText: function ( msgId, args )
		{
			var
				$this = $( this ),
				data = getTranslationData( $( this ) ) ;
			
			data['*'] = {
				'message': msgId,
				'arguments': args,
				'html': false
			} ;
			
			setTranslationData( $( this ), data ) ;
			
			loadLanguage(
				current,
				function ( data )
				{
					core_text.call(
						$this,
						getStringValue( data['*'] )
					) ;
				}
			) ;
			
			return this ;
		},
		
		/** Wrapper for $.fn.text. */
		text: function ( value )
		{
			if ( value !== undefined )
			{
				deleteTranslation( $( this ), '*' ) ;
			}
			
			return core_text.apply( this, arguments ) ;
		},
		
		/** Set or change a translatable content (raw HTML).
		 * @method trHtml
		 * @param {String} msgId The id of the message to use.
		 * @param {Object} args Arguments of the message.
		 */
		trHtml: function ( msgId, args )
		{
			var
				$this = $( this ),
				data = getTranslationData( $( this ) ) ;
			
			data['*'] = {
				'message': msgId,
				'arguments': args,
				'html': true
			} ;
			
			setTranslationData( $( this ), data ) ;
			
			loadLanguage(
				current,
				function ( data )
				{
					core_html.call(
						$this,
						getStringValue( data['*'] )
					) ;
				}
			) ;
			
			return this ;
		},
		
		/** Wrapper for $.fn.html. */
		html: function ( value )
		{
			if ( value !== undefined )
			{
				deleteTranslation( $( this ), '*' ) ;
			}
			
			return core_html.apply( this, arguments ) ;
		}
		
	} ) ;

	/* Load the current language. */
	loadLanguage( current, $.noop ) ;

} ) ( jQuery ) ;
