
/** Language abstraction.
 * @class language
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
		messages = {};

	window.language = {} ;
	
	/** Get the current language.
	 * @method getCurrent
	 */
	window.language.getCurrent = function ()
	{
		return current ;
	} ;

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
		var data = JSON.parse( $node.attr( 'data-translation' ) ) ;
		
		if ( ! $.isPlainObject( data ) )
		{
			data = {} ;
		}
		
		return data ;
	}
	
	$.extend( $.fn, {
		
		/** Set or change a translatable attribute.
		 * @method trAttr
		 * @param {String} attrName The attribute to change.
		 * @param {String} msgId The id of the message to use.
		 * @param {Object} args Arguments of the message.
		 */
		trAttr: function ( attrName, msgId, args )
		{ /* TODO */ },
		
		/** Set or change a translatable content (escaped).
		 * @method trText
		 * @param {String} msgId The id of the message to use.
		 * @param {Object} args Arguments of the message.
		 */
		trText: function ( msgId, args )
		{ /* TODO */ },
		
		/** Set or change a translatable content (raw HTML).
		 * @method trHtml
		 * @param {String} msgId The id of the message to use.
		 * @param {Object} args Arguments of the message.
		 */
		trHtml: function ( msgId, args )
		{ /* TODO */ }
		
	} ) ;

} ) ( jQuery ) ;
