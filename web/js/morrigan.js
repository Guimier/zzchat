/**
 * @module wysiwyg
 * @requires configuration
 */
( function ( $ ) {

'use strict' ;

/**
 * @class extendMorrigan
 * @static
 */

	/**
	 * List of available smileys.
	 * @property {Array} smileys
	 * @private
	 */
	var smileys = [
		'smiley-x',
		'smiley-v',
		'smiley-o'
	] ;

	/**
	 * @method getSmileyUrl
	 * @private
	 * @param {String} smiley Name of the smiley
	 */
	function getSmileyUrl( smiley )
	{
		return 'web/img/' + smiley + '.png' ;
	}

	/**
	 * @method getSmileyInserter
	 * @private
	 * @param {String} smiley Name of the smiley
	 */
	function getSmileyInserter( smiley )
	{
		return {
			name: smiley,
			view: {
				activeBackground: '#aaa',
				inactiveBackground: '#eee',
				title: 'morrigan.' + smiley,
				classes: 'smiley ' + smiley
			},
		
			onClickHandler: function ( editor ) {
				editor._window.document.execCommand(
					'insertImage',
					false,
					getSmileyUrl( smiley )
				);
			}
		} ;
	}

	window.extendMorrigan = {} ;

	var i, smiley, styles = '.smiley{background:no-repeat center}' ;
	
	for ( i in smileys )
	{
		smiley = smileys[i] ;
		extendMorrigan[smiley] = getSmileyInserter( smiley ) ;
		styles += '.' + smiley + '{background-image:url("' + getSmileyUrl( smiley ) + '")}' ;
	}
	
	$( document.head ).append( $( '<style>' )
		.text( styles )
	) ;

/**
 * @class jQuery.wysiwyg
 * @static
 */

	$.extend( $.fn, {
	
		/**
		 * @method wysiwyg
		 * @param {String} type Editor type, one of 'normal' and 'theater'
		 */
		wysiwyg: function ( type )
		{
			var toolbox ;
			
			switch ( type )
			{
				case 'normal' :
					toolbox = [
						[ 'bold', 'italy', 'strike' ],
						[ 'link', 'unLink' ],
						smileys
					] ;
					break ;
				case 'theater' :
					toolbox = [
						[ 'bold', 'strike' ],
						[ 'link', 'unLink' ]
					] ;
					break ;
			}
			
			var $editor = this.morrigan_editor( { // jshint ignore:line
				iframeStyles: 'web/lib/morrigan-iframe.css',
				width: 'auto',
				height: '140px',
				toolbox: [ toolbox ]
			} ) ;
			
			$( $editor.find( '.mrge-content-iframe' )[0].contentDocument )
				.find( 'html' )
				.on( 'keypress', function ( evt ) {
					if ( evt.which === 13 && ! evt.shiftKey )
					{
						$editor.trigger( 'enter', {
							content: $editor.morrigan_editor( 'html' ) // jshint ignore:line
						} ) ;
						$editor.morrigan_editor( 'html', '' ) ; // jshint ignore:line
					}
				} ) ;
			
			return $editor ;
		}
	
	} ) ;

} ) ( jQuery ) ;

