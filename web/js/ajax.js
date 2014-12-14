/**
 * Ajax abstraction.
 * @module ajax
 * @requires languages
 */
 ( function ( $ ) {

'use strict' ;

/**
 * Ajax exceptions.
 * @class ajaxError
 * @constructor
 * @param {String} parsedMessage Parsed message.
 * @param {String} type Exception type ('ajaxError' or PHP’s class name).
 * @param {String} structMessage Message’s structure.
 * @param {Boolean} internal Whether the error is internal or was caused by the user.
 */
	function ajaxError( parsedMessage, type, structMessage, internal )
	{
		/**
		 * Parsed message.
		 * @property {String} message
		 */
		var err = new Error( parsedMessage ) ;
		/**
		 * Exception type ('ajaxError' or PHP’s class name).
		 * @property {String} type
		 */
		err.type = type ;
		/**
		 * Message’s name.
		 * @property {String} msgName
		 */
		err.msgName = structMessage.message ;
		/**
		 * Message’s arguments.
		 * @property {String} msgArgs
		 */
		err.msgArgs = structMessage.arguments ;
		/**
		 * Whether the error is internal or was caused by the user.
		 * @property {Boolean} internal
		 */
		err.internal = internal ;
		
		return err ;
	}

/** Ajax abstraction interface.
 * @class ajax
 * @static
 */
	var
		/**
		 * Waiting query parts
		 * Associative array of ordered arrays.
		 * Key are the query parts names, values are the queues.
		 * @private
		 * @property {Object} queries
		 */
		queries = {},
		
		/**
		 * “Interval” identifier for automatic sending.
		 * @private
		 * @property {number} interval
		 */
		interval = 0 ;
	
	window.ajax = {} ;
	
	/**
	 * Add properties from an object to another with prefixes.
	 * @private
	 * @method extendPrefixed
	 * @param {Object} src Source object.
	 * @param {Object} dest Destination object.
	 * @param {string} prefix Prefix.
	 */
	function extendPrefixed( src, dest, prefix )
	{
		for ( var key in src )
		{
			dest[prefix+key] = src[key] ;
		}
	}
	
	/**
	 * Equivalent of Array.prototype.map for an object.
	 * @private
	 * @method objectMap
	 * @param {Object} obj Object to iterate over.
	 * @param {Function} fn Function to apply on items.
	 */
	function objectMap( obj, fn )
	{
		var res = {}, i, tmp ;
		for ( i in obj )
		{
			tmp = fn( obj[i] ) ;
			if ( tmp !== undefined )
			{
				res[i] = tmp ;
			}
		}
		return res ;
	}
	
	/**
	 * Execute as much as possible of the queued queries.
	 * @private
	 * @method runQuery
	 */
	function runQuery()
	{
		var
			/* List of query parts. */
			parts = [],
			/* Current part. */
			part,
			/* Index. */
			i,
			/* jQuery.ajax parameter */
			ajaxParam = {
				url: 'ajax.php',
				type: 'GET',
				data: {
					language: languages.getCurrent()
				},
				dataType: 'json'
			} ;
		
		for ( i in queries )
		{
			if ( queries[i].length > 0 )
			{
				part = queries[i].shift() ;
				if ( part.method === 'POST' )
				{
					ajaxParam.method = 'POST' ;
				}
				parts.push( part ) ;
				extendPrefixed( part.data, ajaxParam.data, part.name + '_' ) ;
			}
		}
		
		if ( parts.length > 0 )
		{
			ajaxParam.data.query = parts.map( function ( part ) { return part.name ; } ) ;
		
			ajaxParam.data = objectMap( ajaxParam.data, function ( elem ) {
				var res = elem ;
				
				if (  $.isArray( elem ) )
				{
					res = elem.join( ',' ) ;
				}
				else if ( typeof elem === 'boolean' )
				{
					res = elem ? '' : undefined ;
				}
				
				return res ;
			} ) ;
			
			/* Error callback. */
			ajaxParam.error = function ( jqXHR, textStatus )
			{
				var i, err ;
				for ( i in parts )
				{
					err = new Error( 'ajax:' + textStatus ) ;
					err.internal = true ;
					err.type = 'ajaxError' ;
					parts[i].error( err ) ;
				}
			} ;
		
			/* Success callback. */
			ajaxParam.success = function ( data )
			{
				var i, partData ;
				
				for ( i in parts )
				{
					partData = data[parts[i].name] ;
					if ( partData.success )
					{
						if ( typeof partData.data === 'undefined' )
						{
							parts[i].success( null ) ;
						}
						else
						{
							parts[i].success( partData.data ) ;
						}
					}
					else
					{
						parts[i].error( ajaxError(
							partData.message, 
							partData.error,
							partData.struct,
							partData.type !== 'user'
						) ) ;
					}
				}
			} ;
		
			$.ajax( ajaxParam ) ;
		}
	}
	
	/**
	 * Add an Ajax sub-query.
	 * Creates a AjaxQueryPart object.
	 * @method add
	 * @param {string} method 'GET' or 'POST', depending on whet is needed.
	 * @param {string} name Sub-query name.
	 * @param {Object} data Data to send.
	 * @param {Function} [success] Callback on success. Called with the returned data.
	 * @param {Function} [error] Callback on error. Called with an ajaxError object.
	 */
	window.ajax.add = function ( method, name, data, success, error )
	{
		var query = new AjaxQueryPart( method, name, data, success, error ) ;
		
		if ( ! $.isArray( queries[name] ) )
		{
			queries[name] = [] ;
		}
		
		queries[name].push( query ) ;
	} ;
	
	/**
	 * Send an Ajax sub-query.
	 * Will wait for the next request if the automatic sending is on.
	 * @method send
	 * @param {string} method 'GET' or 'POST', depending on whet is needed.
	 * @param {string} name Sub-query name.
	 * @param {Object} data Data to send.
	 * @param {Function} [success] Callback on success. Called with the returned data.
	 * @param {Function} [error] Callback on error. Called with error name and description.
	 */
	window.ajax.send = function ( method, name, data, success, error ) // jshint ignore:line
	{
		this.add.apply( this, arguments ) ;
		
		if ( ! this.isAutomaticOn() )
		{
			this.sendNow() ;
		}
	} ;
	
	/**
	 * Check whether automatic sending is on or off.
	 * @method isAutomaticOn
	 */
	window.ajax.isAutomaticOn = function ()
	{
		return interval !== 0 ;
	} ;
	
	/**
	 * Stop automatic sending of queries.
	 * @method stop
	 */
	window.ajax.stop = function ()
	{
		if ( this.isAutomaticOn() )
		{
			clearInterval( interval ) ;
			interval = 0 ;
		}
	} ;
	
	/**
	 * Start automatic sending of queries.
	 * @method start
	 * @param {number} period Time between two sendings (seconds).
	 */
	window.ajax.start = function ( period )
	{
		this.stop() ;
		interval = setInterval( runQuery, period * 100 ) ;
	} ;
	
	/**
	 * Send a query now.
	 * @method sendNow
	 */
	window.ajax.sendNow = runQuery ;
	
	/**
	 * Send an Ajax sub-query at a fixed interval.
	 * @method interval
	 * @param {Number} seconds Seconds between each execution.
	 * @param {String} method 'GET' or 'POST', depending on whet is needed.
	 * @param {String} name Sub-query name.
	 * @param {Function} [dataBuilder] Function building the data to send.
	 * @param {Function} [success] Callback on success. Called with the returned data.
	 * @param {Function} [error] Callback on error. Called with error name and description.
	 * @return An interval handler. Stop this interval with `clearInterval`.
	 */
	window.ajax.interval = function ( seconds, method, name, dataBuilder, success, error ) // jshint ignore:line
	{
		var answered = true ;
		
		function callback( real )
		{
			return function () {
				answered = true ;
				real.apply( this, arguments ) ;
			} ;
		}
		
		return setInterval(
			function ()
			{
				if ( answered )
				{
					answered = false ;
					ajax.send(
						method, name,
						$.isFunction( dataBuilder ) ? dataBuilder() : {},
						callback( success || $.noop ),
						callback( error || $.noop )
					) ;
				}
			},
			seconds * 1000
		) ;
	} ;

/**
 * Ajax query part.
 * @class AjaxQueryPart
 * @constructor
 * @param {string} method 'GET' or 'POST', depending on whet is needed.
 * @param {string} name Sub-query name.
 * @param {Object} data Data to send.
 * @param {Function} [success] Callback on success. Called with the returned data.
 * @param {Function} [error] Callback on error. Called with error name.
 */
	function AjaxQueryPart( method, name, data, success, error )
	{
		if ( method !== 'GET' && method !== 'POST' )
		{
			throw new Error( 'AjaxQueryBadMethod' ) ;
		}
		
		this.method = method ;
		
		this.name = name ;
		
		if ( $.isPlainObject( data ) )
		{
			this.data = data ;
		}
		
		if ( $.isFunction( success ) )
		{
			this.success = success ;
		}
		
		if ( $.isFunction( error ) )
		{
			this.error = error ;
		}
	}
	
	AjaxQueryPart.prototype = {
		
		/**
		 * HTTP method.
		 * @property {String} method
		 */
		method: null,
		
		/**
		 * Query part name.
		 * @property {String} name
		 */
		name: null,
		
		/**
		 * Query data.
		 * @property {Object} data
		 */
		data: {},
		
		/**
		 * Success callback.
		 * @property {Function} success
		 */
		success: $.noop,
		
		/**
		 * Error callback.
		 * @property {Function} error
		 */
		error: function ( err )
		{
			if ( console && $.isFunction( console.error ) )
			{
				console.error( 'Error on query part “' + this.name + '”: ' + err.message ) ;
			}
		}
		
	} ;

} ) ( jQuery ) ;
