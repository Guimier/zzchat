
/** Language abstraction.
 * @class language
 */
( function ( $ ) {

	var
		/** Current language code.
		 * @private
		 * @property {String} current
		 */
		current = 'en' ;

	window.language = {} ;
	
	/** Get the current language.
	 * @method getCurrent
	 */
	window.language.getCurrent = function ()
	{
		return current ;
	} ;

} ) ( jQuery ) ;
