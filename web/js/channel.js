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
		
		this.updatePresents() ;
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
			}
		},
		
		/**
		 * Remove the channel from the web interface.
		 * @method remove
		 */
		remove: function ()
		{
			this.$body.remove() ;
			this.$tab.remove() ;
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
		},
		
		/**
		 * Update channel’s meta-information.
		 * @method updateData
		 * @param {Object} data Ajax channel representation.
		 */
		updateData: function ( data )
		{
			$.extend( this, data ) ;
			this.updatePresents() ;
		}
		
	} ;
	
/**
 * Manage the channels.
 * @class channels
 * @static
 */
	
	var 
		/**
		 * Openned channels.
		 * @param {Object} opennedChannels
		 * @private
		 */
		opennedChannels = {},
		
		/**
		 * Interval reference for posts update.
		 * @param postsInterval
		 * @private
		 */
		postsInterval = 0 ,
		
		/**
		 * Interval reference for metadata update.
		 * @param metaInterval
		 * @private
		 */
		metaInterval = 0 ;
	
	/**
	 * Know whether a channel has been openned.
	 * @method channelIsOppened
	 * @private
	 * @param {Number} id The channel identifiant.
	 */
	function channelIsOppened( id )
	{
		return typeof opennedChannels[id] !== 'undefined' ;
	}
	
	/**
	 * Ajax callback for channel data load.
	 * @method gotChannelsData
	 * @private
	 * @param {Object} data Data returned by the server.
	 */
	function gotChannelsData( data )
	{
		var id ;
		
		for ( id in data )
		{
			if ( channelIsOppened( id ) )
			{
				opennedChannels[id].updateData( data[id] ) ;
			}
		}
	}
	
	/**
	 * Ajax callback for channel data first load.
	 * @method gotChannelsFirstData
	 * @private
	 * @param {Object} data Data returned by the server.
	 */
	function gotChannelsFirstData( data )
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
	
	/**
	 * Get meta information for some channels.
	 * @method getChannelsData
	 * @param {Number} ids List of channels identifiants.
	 * @param {Function} callback Callback to call when data is available.
	 */
	function getChannelsData( ids, callback )
	{
		ajax.send( 'GET', 'channel', { id: ids }, callback ) ;
	}
	
	/**
	 * Get metadata to update channels.
	 */
	function getMetadata()
	{
		getChannelsData( Object.keys( opennedChannels ), gotChannelsData )
	}
	
	window.channels = {} ;
	
	/**
	 * Open channels.
	 * @method open
	 * @param {Number} id* Id of a channel to open.
	 */
	window.channels.open = function ( /* id* */ )
	{
		var ids = Array.prototype.filter.call(
			arguments,
			function ( id )
			{
			 	return ! channelIsOppened( id ) ;
			}
		) ;
		
		getChannelsData( ids, gotChannelsFirstData ) ;
	} ;
	
	/**
	 * Start chat.
	 * @method start
	 */
	window.channels.start = function ()
	{
		ajax.start( 2 ) ;
		postsInterval = setInterval( $.noop, configuration.get( 'postsrate' ) * 1000 ) ;
		metaInterval = setInterval( getMetadata, configuration.get( 'metarate' ) * 1000 ) ;
		this.open.apply( this, configuration.get( 'channels' ) ) ;
	} ;
	
	/**
	 * Stop chat.
	 * @method stop
	 */
	window.channels.stop = function ()
	{
		ajax.stop() ;
		clearInterval( postsInterval ) ;
		clearInterval( metaInterval ) ;
	} ;
	
} ) ( jQuery ) ;
