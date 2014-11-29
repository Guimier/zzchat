/**
 * @module wysiwyg
 */
( function ( $ ) {

	/**
	 * @class morrigan
	 */
	window.morrigan = {} ;

	/**
	 * @property {Array} smileys List of available smileys
	 */
	morrigan.smileys = [
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
				title: 'smiley-name…',
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
	for ( i in morrigan.smileys )
	{
		smiley = morrigan.smileys[i] ;
		morrigan_ext[smiley] = getSmileyInserter( smiley );
		styles += '.' + smiley + '{background-image:url("' + getSmileyUrl( smiley ) + '")}' ;
	}
	
	$( document.head ).append( $( '<style>' )
		.text( styles )
	) ;
	console.log( styles ) ;

} ) ( jQuery ) ;
