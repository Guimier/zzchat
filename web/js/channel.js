/**
 * Channels management.
 * @module channels
 */
( function ( $ ) {

'use strict' ;
	
/**
 * @class Channel
 * @private
 * @param {Object} data Description of the channel as returned by Ajax `channel` query.
 */
	function Channel( ajaxData )
	{
		var that = this ;
		
		$.extend( this, ajaxData ) ;
		
		/* Add the tab. */
		this.$tab = $( '<li>' )
			.text( this.name )
			.click( function ( evt ) {
				evt.preventDefault() ;
				that.show() ;
			} ) ;
		$( '#channels-list-actives' ).append( this.$tab ) ;
		
		/* Create the WYSIWYG surface. */
		this.$wysiwyg = $( '<div>' ).addClass( 'wysiwyg' ) ;
		
		/* Create the presents list. */
		this.$presents = $( '<ul>' ).addClass( 'presents' ) ;
		
		/* Add the body. */
		this.$body = $( '<div>' )
			.addClass( 'channel' )
			.attr( 'id', 'channel-' + this.id )
			.append( $( '<div>' ).addClass( 'channelCore' )
				.append( $( '<div>' ).addClass( 'messages' ) )
				.append( this.$wysiwyg )
			)
			.append( this.$presents ) ;
		$( '#channels' ).append( this.$body ) ;
	}
	
	Channel.prototype = {
	
	/***** Inherited from Ajax answers. *****/
	
		/**
		 * The channel’s id.
		 * @property {Number} id
		 * @private
		 */
		id: null,
		
		/**
		 * The channel’s name.
		 * @property {String} name
		 * @private
		 */
		name: null,
		
		/**
		 * The channel’s title.
		 * @property {String} title
		 * @private
		 */
		title: null,
		
		/**
		 * The present users.
		 * @property {Array} users
		 * @private
		 */
		users: [],
		
	/***** JavaScript-specific. *****/
	
		/**
		 * The channel’s main element.
		 * @property {jQuery} $body
		 * @private
		 */
		$body: null,
		
		/**
		 * The channel’s WYSIWYG surface.
		 * @property {jQuery} $wysiwyg
		 * @private
		 */
		$wysiwyg: null,
		
		/**
		 * The channel’s tab.
		 * @property {jQuery} $tab
		 * @private
		 */
		$tab: null,
		
		/**
		 * Have the channel  been already shown.
		 * @property {Boolean} shown
		 * @private
		 */
		shown: false,
		
		/**
		 * Show the channel.
		 * @method show
		 */
		show: function ()
		{
			/* Show only this channel’s body. */
			this.$body.siblings().removeClass( 'currentChannel' ) ;
			this.$body.addClass( 'currentChannel' ) ;
			
			/* Only this channel’s tab is the current one. */
			this.$tab.siblings().removeClass( 'current' ) ;
			this.$tab.addClass( 'current' ) ;
			
			/* Set up the WYSIWYG if not already done. */
			if ( ! this.shown )
			{
				this.shown = true ;
				this.$wysiwyg.wysiwyg( this.type ) ;
				this.updatePresents() ;
			}
		},
		
		/**
		 * Update the list of the present users.
		 * @method updatePresents
		 * @private
		 */
		updatePresents: function()
		{
			var
				$presents = $( [] ),
				i ;
			
			for ( i in this.users )
			{
				$presents = $presents.add( $( '<li>' )
					.text( this.users[i].name )
				) ;
			}
			
			this.$presents.html( $presents ) ;
		}
		
	} ;
	
/**
 * Manage the channels.
 * @class channels
 * @static
 */
	
	var opennedChannels = {} ;
	
	function channelIsOppened( id )
	{
		return typeof opennedChannels[id] !== 'undefined' ;
	}
	
	function gotChannelData( data )
	{
		var id ;
		
		for ( id in data )
		{
			if ( ! channelIsOppened( id ) )
			{
				opennedChannels[id] = new Channel( data[id] ) ;
			}
		}
		
		/* Show the las one. */
		opennedChannels[id].show() ;
	}
	
	window.channels = {} ;
	
	/**
	 * Open channels.
	 * @method open
	 * @param {Number} id* Id of a channel to open.
	 */
	window.channels.open = function ( /* ids */ )
	{
		var ids = Array.prototype.filter.call(
			arguments,
			function ( id )
			{
			 	return ! channelIsOppened( id ) ;
			}
		) ;
		
		ajax.send(
			'GET',
			'channel',
			{ id: ids },
			gotChannelData
		) ;
	} ;
	
	/* TODO Write previous channels opening. */
	channels.open( 1 ) ;
	channels.open( 2 ) ;
	channels.open( 3 ) ;
	
} ) ( jQuery ) ;
