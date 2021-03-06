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
		this.$close = $( '<span>' )
			.addClass( 'close' )
			.click( function () { that.onClose.apply( that, arguments ) ; } ) ;
		this.$name = $( '<span>' ).text( this.name ) ;
		this.$tab = $( '<li>' )
			.append( this.$close )
			.append( this.$name )
			.click( function ( evt ) {
				evt.preventDefault() ;
				that.show() ;
			} ) ;
		$( '#channels-list-actives' ).append( this.$tab ) ;
		
		this.$error = $( '<div>' ).addClass( 'error' ).hide() ;
		
		/* Create the WYSIWYG surface. */
		this.$wysiwyg = $( '<div>' )
			.addClass( 'wysiwyg' )
			.on( 'enter', function () { that.onEnter.apply( that, arguments ) ; } );
		
		/* Create the presents list. */
		this.$presents = $( '<ul>' ).addClass( 'presents' ) ;
		
		/* Create the messages list. */
		this.$posts = $( '<div>' ) .addClass( 'messages' ) ;
		this.$posts.append($( '<p>' )
			.addClass( 'title' )
			.text( this.title )
		) ;
		
		/* Add the body. */
		this.$body = $( '<div>' )
			.addClass( 'channel' )
			.addClass( 'channel-' + this.type )
			.attr( 'id', 'channel-' + this.id )
			.append( $( '<div>' ).addClass( 'channelCore' )
				.append( this.$posts )
				.append( this.$error )
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
		 * The channel’s error container.
		 * @property {jQuery} $error
		 * @private
		 */
		$error: null,
		
		/**
		 * Reference to the delay closing the error.
		 * @property {Number} errorCloser
		 * @private
		 */
		errorCloser: 0,
		
		/**
		 * The channel’s name container.
		 * @property {jQuery} $name
		 * @private
		 */
		$name: null,
		
		/**
		 * The channel’s close button.
		 * @property {jQuery} $close
		 * @private
		 */
		$close: null,
		
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
		 * Array containing the id of the posts which have already been shown.
		 * @property {Array} shownPosts
		 * @private
		 */
		shownPosts: [],
		
		/**
		 * Is the channel visible ?
		 * @method isVisible
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
				.removeClass( 'new' )
				.addClass( 'current' ) ;
			this.$name.text( this.name ) ;
			this.unread = 0 ;
			
			/* Set up the WYSIWYG if not already done. */
			if ( ! this.shown )
			{
				var mcsOpts = {
					theme: 'dark-3',
					alwaysShowScrollbar: 2,
					scrollInertia: 0,
					autoDraggerLength: true,
					scrollButtons:{ enable: true }
				} ;
				
				this.shown = true ;
				this.$wysiwyg.wysiwyg( this.type ) ;
				
				this.$posts.mCustomScrollbar( mcsOpts ) ;
				this.$posts = this.$posts.find( '.mCSB_container' ) ;
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
		
		closeError: function ()
		{
			this.$error.hide() ;
			this.errorCloser = 0 ;
		},
		
		onEnter: function ( evt, content )
		{
			var that = this ;
			
			clearInterval( this.errorCloser ) ;
			this.closeError() ;
			
			ajax.add(
				'POST', 'post',
				{
					channel: this.id,
					content: content
				},
				$.noop,
				function ( error )
				{
					that.$error.trText( error.msgName, error.msgArgs ).show() ;
					that.errorCloser = setTimeout( function () { that.closeError() ; }, 10000 ) ;
				}
			) ;
		},
		
		onClose: function ( evt )
		{
			evt.stopPropagation() ;
			close( this.id ) ;
		},
		
		twoChars: function( num )
		{
			return num < 10 ? '0' + num : num ;
		},
		
		formatDate: function ( date )
		{
			return this.twoChars( date.getHours() ) +
				':' + this.twoChars( date.getMinutes() ) +
				':' + this.twoChars( date.getSeconds() ) ;
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
			var i, post, postIds = [] ;
			
			for ( i = 0 ; i < posts.length ; ++ i )
			{
				post = posts[i] ;
				if ( this.shownPosts.indexOf( post.id ) < 0 )
				{
					if ( post.owner.id !== this.lastUser )
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
					postIds.push( post.id ) ; 
				}
			}
				
			this.$posts.mCustomScrollbar( 'update' ) ;
			
			this.shownPosts = postIds ;
			
			if ( ! this.isVisible() && posts.length )
			{
				this.unread += posts.length ;
				this.$tab.addClass( 'new' ) ;
				this.$name.trText( 'channels.new', { num: this.unread, name: this.name } ) ;
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
		 * Opened channels.
		 * @property {Object} openedChannels
		 * @private
		 */
		openedChannels = {},
		
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
		 * Interval reference for active channels update.
		 * @property channelsInterval
		 * @private
		 */
		channelsInterval = 0,
		
		/** List of active channels.
		 * @property activeChannels
		 * @private
		 */
		activeChannels = {},
		
		/**
		 * Last date of update of new posts.
		 * @property lastUpdateDate
		 * @private
		 */
		lastUpdateDate = Infinity ;
	
	/**
	 * List opened channels.
	 * @method listOpenedChannels
	 * @private
	 */
	function listOpenedChannels()
	{
		return Object.keys( openedChannels ) ;
	}
	
	/**
	 * Know whether a channel has been opened.
	 * @method channelIsOpened
	 * @private
	 * @param {Number} id The channel identifiant.
	 */
	function channelIsOpened( id )
	{
		return openedChannels[id] instanceof Channel ;
	}
	
	/**
	 * Open a channel (callback for closed list).
	 * @method openThis
	 * @private
	 */
	function openThis()
	{
		// jshint validthis: true
		open(
			$( this ).remove().attr( 'data-id' )
		) ;
	}
	
	/**
	 * Update the list of closed active channels.
	 * @method updateActiveChannels
	 * @private
	 */
	function updateActiveChannels()
	{
		var id, $channels = $( [] ) ;
		
		for ( id in activeChannels )
		{
			if ( ! channelIsOpened( id ) )
			{
				$channels = $channels.add( $( '<li>' )
					.text( activeChannels[id] )
					.attr( 'data-id', id )
					.click( openThis )
				) ;
			}
		}
		
		$( '#channels-list-inactives' ).html( $channels ) ;
	}
	
	/**
	 * Ajax callback for active channels list load.
	 * @method gotActiveChannels
	 * @private
	 * @param {Object} data Data returned by the server.
	 */
	function gotActiveChannels( data )
	{
		var id, channels = {} ;
		
		for ( id in data )
		{
			channels[data[id].id] = data[id].name ;
		}
		
		activeChannels = channels ;
		updateActiveChannels() ;
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
			if ( channelIsOpened( id ) )
			{
				openedChannels[id].updateData( data[id] ) ;
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
			if ( channelIsOpened( i ) )
			{
				openedChannels[i].newPosts( data.posts[i] ) ;
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
			if ( ! channelIsOpened( id ) )
			{
				openedChannels[id] = new Channel( data[id] ) ;
			}
		}
		
		/* Show the las one. */
		openedChannels[id].show() ;
		configuration.setLocal( 'channels', listOpenedChannels() ) ;
	}
	
	/**
	 * Close a channel.
	 * @method closeChannel
	 * @private
	 * @param {Number} id The channel’s identifiant.
	 */
	function closeChannel( id )
	{
		openedChannels[id].remove() ;
		delete openedChannels[id] ;
	}
	
	/**
	 * Open channels.
	 * @method open
	 * @private
	 * @param {Number} id* Id of a channel to open.
	 */
	function open( /* id* */ )
	{
		var ids = Array.prototype.filter.call(
			arguments,
			function ( id )
			{
			 	return ! channelIsOpened( id ) ;
			}
		) ;
		
		ajax.send( 'GET', 'channel', { id: ids }, gotChannelsFirstData ) ;
	}
	
	/**
	 * Close channels.
	 * @method close
	 * @private
	 * @param {Number} id* Id of a channel to close.
	 */
	function close( /* id* */ ) // jshint ignore:line
	{ // (jshint ignore explanation) Used in Channel::onClose
		var i, list, showAnother = false ;
		
		for ( i = 0 ; i < arguments.length ; ++ i )
		{
			if ( channelIsOpened( arguments[i] ) )
			{
				if ( openedChannels[arguments[i]].isVisible() )
				{
					showAnother = true ;
				}
				closeChannel( arguments[i] ) ;
			}
		}
		
		list = listOpenedChannels() ;
		configuration.setLocal( 'channels', list ) ;
		
		if ( showAnother )
		{
			if ( list.length > 0 )
			{
				openedChannels[list[0]].show() ; // We may be smarter here, but it works
			}
			else
			{
				open( -1 ) ; // Open the default channel
			}
		}
	}
	
	window.channels = {} ;
	
	/**
	 * Start chat.
	 * @method start
	 */
	window.channels.start = function ()
	{
		var backlog = configuration.get( 'backlog' ) ;
		ajax.send(
			'GET', 'date', null,
			/* Don’t believe the client. */
			function ( date ) { lastUpdateDate = date - backlog ; },
			/* OK, believe the client. */
			function () { lastUpdateDate = $.now().getTime() / 1000 - backlog ; }
		) ;
		ajax.start( 2 ) ;
		
		channelsInterval = ajax.interval(
			configuration.get( 'channelsrate' ),
			'GET', 'activeChannels',
			$.noop,
			gotActiveChannels,
			null,
			true
		) ;
		
		postsInterval = ajax.interval(
			configuration.get( 'postsrate' ),
			'GET', 'lastPosts',
			function ()
			{
				return {
					channels: listOpenedChannels(),
					from: lastUpdateDate
				} ;
			},
			gotLastPosts
		) ;
		
		metaInterval = ajax.interval(
			configuration.get( 'metarate' ),
			'GET', 'channel',
			function () { return { id: listOpenedChannels() } ; },
			gotChannelsData
		) ;
		
		open.apply( this, configuration.get( 'channels' ) ) ;
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
		
		$.each( openedChannels, closeChannel ) ;
	} ;

/*----- Channels creation. -----*/

	function insertDialog()
	{
		var
			$nameLabel = $( '<label>' )
				.attr( 'for', 'create-channel-name' )
				.trText( 'channels.create.name' ),
			
			$nameInput = $( '<input>' )
				.attr( {
					id: 'create-channel-name',
					type: 'text'
				} ),
			
			$titleLabel = $( '<label>' )
				.attr( 'for', 'create-channel-title' )
				.trText( 'channels.create.title' ),
			
			$titleInput = $( '<input>' )
				.attr( {
					id: 'create-channel-title',
					type: 'text'
				} ),
			
			$typeLabel = $( '<label>' )
				.attr( 'for', 'create-channel-type' )
				.trText( 'channels.create.type' ),
			
			$typeSelect = $( '<select>' )
				.attr( 'id', 'create-channel-type' ),
			
			$doButton = $( '<input>' )
				.attr( {
					id: 'create-channel-do',
					type: 'button'
				} )
				.trAttr( 'value', 'channels.create.do' )
				.click(
					function ()
					{
						var
							data = { name: $nameInput.val(), title: $titleInput.val() },
							type = $typeSelect.val() ;
						
						if ( type !== 'default' )
						{
							data.type = type ;
						}
						
						$error.hide() ;
						
						ajax.add(
							'POST', 'createChannel', data,
							function ( data )
							{
								openedChannels[data.id] = new Channel( data ) ;
								openedChannels[data.id].show() ;
								$createDialog.dialog( 'close' ) ;
								
								$nameInput.val( null ) ;
								$titleInput.val( null ) ;
								$typeSelect.val( configuration.get( 'defaulttype' ) ) ;
							},
							function ( error )
							{
								$error.show().trText( error.msgName, error.msgArgs ) ;
							}
						) ;
					}
				),
			
			$error = $( '<p>' ).addClass( 'error' ).hide(),
			
			$createDialog = $( '<div>' )
				.attr( 'id', 'create-channel' )
				.hide()
				.append( $nameLabel )
				.append( $nameInput )
				.append( $titleLabel )
				.append( $titleInput )
				.append( $typeLabel )
				.append( $typeSelect )
				.append( $doButton )
				.append( $error ) ;
			
		function addTypeOption( type )
		{
			$typeSelect.append( $( '<option>' )
				.val( type )
				.attr( 'id', 'create-channel-type-' + type )
				.prop( 'selected', type === configuration.get( 'defaulttype' ) )
				.trText( 'channels.create.type.' + type )
			) ;
		}
		
		addTypeOption( 'normal' ) ;
		addTypeOption( 'theater' ) ;
		
		$( document.body ).append( $createDialog ) ;
		$createDialog.dialog( { autoOpen: false } ) ;
	
		$createDialog
			.closest( '.ui-dialog' )
			.find( '.ui-dialog-title' )
			.trText( 'channels.create.wintitle' ) ;
	
		$( '#channels-title' ).trText( 'channels.inactives' ) ;
	
		$( '#channels-new' )
			.trAttr( 'value', 'channels.create' )
			.click(
				function ()
				{
					$createDialog.dialog( 'open' ) ;
				}
			) ;
	}
	
	$( document ).ready( insertDialog ) ;
	
} ) ( jQuery ) ;
