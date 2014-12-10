( function ( $ ) {
	
	var openChannels = {} ;
	
	function gotChannelData( ajaxData )
	{
		new Channel( ajaxData ) ;
	}
	
	function errorChannelData()
	{
		
	}
	
	
	
	function Channel( ajaxData )
	{
		openChannels[ajaxData.id] = this ;
		
		this.$element = $( '<div>' )
			.attr( 'id', 'channel-' + ajaxData.id )
			.append( $( '<div>' )
				.append( $( '<div>' )
					.addClass( 'messages' )
				)
				.append( $( '<div>' )
					.addClass( 'wysiwyg' )
					.wysiwyg( ajaxData.type )
				)
			.append( $( '<div>' )
				.addClass( 'present' )
			)
		) ;
	}
	
		
	Channel.prototype = {
	
			$element: null,
			$tab = null
	} ;
	
	window.channels = {} ;
	
	channels.openChannel = function ( name )
	{
		ajax.send(
			'GET',
			'channel',
			{ name: name },
			gotChannelData,
			errorChannelData
		) ;
	} ;
	
} ) ( jQuery ) ;
