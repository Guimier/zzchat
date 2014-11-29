/**
 * @module wysiwyg
 */
( function ( $ ) {

/**
 * @class morrigan-ext
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
		
			onClickHandler: function ( editor, action ) {
				editor._window.document.execCommand(
					'insertImage',
					false,
					getSmileyUrl( smiley )
				);
			}
		}
	}

	window.morrigan_ext = {} ;

	var smiley, styles = '.smiley{background:no-repeat center}';
	for ( i in smileys )
	{
		smiley = smileys[i] ;
		morrigan_ext[smiley] = getSmileyInserter( smiley );
		styles += '.' + smiley + '{background-image:url("' + getSmileyUrl( smiley ) + '")}' ;
	}
	
	$( document.head ).append( $( '<style>' )
		.text( styles )
	) ;

/**
 * @class jQuery.wysiwyg
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
			
			var $editor = this.morrigan_editor( {
				iframeStyles: 'web/lib/morrigan-iframe.css',
				toolbox: [ toolbox ]
			} ) ;
			
			$( $editor.find( '.mrge-content-iframe' )[0].contentDocument )
				.find( 'html' )
				.on( 'keypress', function ( evt ) {
					if ( evt.which === 13 )
					{
						$editor.trigger( 'enter', {
							content: $editor.morrigan_editor( 'html' )
						} ) ;
						$editor.morrigan_editor( 'html', '' ) ;
					}
				} ) ;
			
			return $editor ;
		}
	
	} ) ;

} ) ( jQuery ) ;

