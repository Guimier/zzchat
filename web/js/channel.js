/**
 * Channels management.
 * @module channels
 * @requires ajax, configuration
 */
( function ( $ ) {

'use strict' ;
	
/**
 * @class Channel
 * @private
 * @constructor
 * @param {Object} data Description of the channel as returned by Ajax `channel` query.
 */
	function Channel( data )
	{
		var that = this ;
		
		$.extend( this, data ) ;
		
		/* Add the tab. */
		this.$tab = $( '<li>' )
			.text( this.name )
			.click( function ( evt ) {
				evt.preventDefault() ;
				that.show() ;
			} ) ;
		$( '#channels-list-actives' ).append( this.$tab ) ;
		
		/* Create the WYSIWYG surface. */
		this.$wysiwyg = $( '<div>' )
			.addClass( 'wysiwyg' )
			.on( 'enter', function () { that.onEnter.apply( that, arguments ) ; } );
		
		/* Create the presents list. */
		this.$presents = $( '<ul>' ).addClass( 'presents' ) ;
		
		/* Create the messages list. */
		this.$posts = $( '<div>' ).addClass( 'messages' ) ;
		
		/* Add the body. */
		this.$body = $( '<div>' )
			.addClass( 'channel' )
			.attr( 'id', 'channel-' + this.id )
			.append( $( '<div>' ).addClass( 'channelCore' )
				.append( this.$posts )
				.append( this.$wysiwyg )
			)
			.append( this.$presents ) ;
		$( '#channels' ).append( this.$body ) ;
		
		this.updatePresents() ;
	}
	
	Channel.prototype = {
	
	/*----- Inherited from Ajax answers. -----*/
	
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
		
	/*----- JavaScript-specific. -----*/
	
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
		 * The channel’s posts container.
		 * @property {jQuery} $posts
		 * @private
		 */
		$posts: null,
		
		/**
		 * Identifiant of the last user who posted on this channel.
		 * @property {Number} lastUser
		 * @private
		 */
		lastUser: null,
		
		/**
		 * Have the channel  been already shown.
		 * @property {Boolean} shown
		 * @private
		 */
		shown: false,
		
		/**
		 * Number of unread posts.
		 * @property {Number} unread
		 * @private
		 */
		unread: 0,
		
		/**
		 * Is the channel visible ?
		 * @method isVisible
		 * @private
		 */
		isVisible: function ()
		{
			return this.$body.hasClass( 'currentChannel' ) ;
		},
		
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
			this.$tab
				.text( this.name )
				.removeClass( 'new' )
				.addClass( 'current' ) ;
			this.unread = 0 ;
			
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
		},
		
		/**
		 * TODO
		 */
		onEnter: function ( evt, content )
		{
			ajax.add(
				'POST', 'post',
				{
					channel: this.id,
					content: content
				}
			) ;
		},

		formatDate: function ( date )
		{
			return date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds() ;
		},
		
		$date: function ( date )
		{
			return $( '<time>' )
				.text( this.formatDate( date ) ) ;
		},
		
		/**
		 * Display new ports.
		 * @method newPosts
		 * @param {Array} posts List of new posts.
		 */
		newPosts: function( posts )
		{
			var i, post ;
			
			for ( i = 0 ; i < posts.length ; ++ i )
			{
				post = posts[i] ;
				
				if ( post.owner.id != this.lastUser )
				{
					this.lastUser = post.owner.id ;
					this.$posts.append( $( '<p>' )
						.addClass( 'speaker' )
						.text( post.owner.name )
					) ;
				}
				
				this.$posts.append( $( '<p>' )
					.addClass( 'message' )
					.html( post.content )
					.prepend( this.$date(
						new Date( post.date * 1000 )
					) )
				) ;
			}
			
			if ( ! this.isVisible() && posts.length )
			{
				this.unread += posts.length ;
				this.$tab
					.trText( 'channels.new', { num: this.unread, name: this.name } )
					.addClass( 'new' ) ;
			}
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
		 * @property {Object} opennedChannels
		 * @private
		 */
		opennedChannels = {},
		
		/**
		 * Interval reference for posts update.
		 * @property postsInterval
		 * @private
		 */
		postsInterval = 0 ,
		
		/**
		 * Interval reference for metadata update.
		 * @property metaInterval
		 * @private
		 */
		metaInterval = 0,
		
		/**
		 * Last date of update of new posts.
		 * @property lastUpdateDate
		 * @private
		 */
		lastUpdateDate = Infinity ;
	
	/**
	 * List openned channels.
	 * @method listChannels
	 * @private
	 */
	function listChannels()
	{
		return Object.keys( opennedChannels ) ;
	}
	
	/**
	 * Know whether a channel has been openned.
	 * @method channelIsOppened
	 * @private
	 * @param {Number} id The channel identifiant.
	 */
	function channelIsOppened( id )
	{
		return opennedChannels[id] instanceof Channel ;
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
	 * Ajax callback for last posts load.
	 * @method gotLastPosts
	 * @private
	 * @param {Object} data Data returned by the server.
	 */
	function gotLastPosts( data )
	{
		var i ;
		lastUpdateDate = data.date ;
		
		for ( i in data.posts )
		{
			if ( channelIsOppened( i ) )
			{
				opennedChannels[i].newPosts( data.posts[i] ) ;
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
	 * Close a channel.
	 * @method closeChannel
	 * @private
	 * @param {Number} id The channel’s identifiant.
	 */
	function closeChannel( id )
	{
		opennedChannels[id].remove() ;
		delete opennedChannels[id] ;
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
		
		ajax.send( 'GET', 'channel', { id: ids }, gotChannelsFirstData ) ;
	} ;
	
	/**
	 * Start chat.
	 * @method start
	 */
	window.channels.start = function ()
	{
		ajax.start( 2 ) ;
		
		// Mhh… don’t believe the client!
		lastUpdateDate = 0 // Math.floor( ( new Date ).getTime() / 1000 ) ;
		
		postsInterval = ajax.interval(
			configuration.get( 'postsrate' ),
			'GET', 'lastPosts',
			function ()
			{
				return {
					channels: listChannels(),
					from: lastUpdateDate
				} ;
			},
			gotLastPosts
		) ;
		
		metaInterval = ajax.interval(
			configuration.get( 'metarate' ),
			'GET', 'channel',
			function () { return { id: listChannels() } ; },
			gotChannelsData
		) ;
		
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
		
		$.each( opennedChannels, closeChannel ) ;
	} ;
	
} ) ( jQuery ) ;
