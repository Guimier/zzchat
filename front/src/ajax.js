/** Ajax abstraction.
 * @class ajax
 */
 ( function ( $ ) {

	var
		/** Waiting query parts
		 * Associative array of ordered arrays.
		 * Key are the query parts names, values are the queues.
		 * @private
		 * @property {Object} queries
		 */
		queries = {},
		
		/** “Interval” identifier for automatic sending.
		 * @private
		 * @property {number} interval
		 */
		interval = 0 ;
	
	window.ajax = {} ;
	
	
	/** Add properties from an object to another with prefixes.
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
	
	/** Equivalent of Array.prototype.map for an object.
	 * @private
	 * @method objectMap
	 * @param {Object} obj Object to iterate over.
	 * @param {Function} fn Function to apply on items.
	 */
	function objectMap( obj, fn )
	{
		var res = {}, i ;
		for ( i in obj )
		{
			res[i] = fn( obj[i] ) ;
		}
		return res ;
	}
	
	/** Execute as much as possible of the queued queries.
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
			/* Data to send */
			data = {},
			/* HTTP methos. */
			method = 'GET' ;
		
		for ( i in queries )
		{
			if ( queries[i].length > 0 )
			{
				part = queries[i].shift() ;
				parts.push( part ) ;
				console.warn( data, part.name ) ;
				extendPrefixed( part.data, data, part.name + '_' ) ;
				console.warn( data ) ;
			}
		}
		
		if ( parts.length > 0 )
		{
			data.query = parts.map( function ( part ) { return part.name ; } ) ;
		
			data = objectMap( data, function ( elem ) {
				return $.isArray( elem ) ? elem.join( ',' ) : elem ;
			} ) ;
			
			/* Error callback. */
			function error( jqXHR, textStatus )
			{
				var i ;
				for ( i in parts )
				{
					parts[i].error( 'ajax:' + textStatus ) ;
				}
			}
		
			/* Success callback. */
			function success( data )
			{
				var i ;
				
				for ( i in parts )
				{
					console.log( parts[i].name ) ;
					if ( data[parts[i].name].success )
					{
						parts[i].success( data[parts[i].name].data ) ;
					}
					else
					{
						parts[i].error( data[parts[i].name].error ) ;
					}
				}
			}
		
			$.ajax( {
				url: 'ajax.php',
				type: method,
				data: data,
				dataType: 'json',
				success: success,
				error: error
			} ) ;
		}
	}
	
	/** Add an Ajax sub-query.
	 * Create a AjaxQueryPart object.
	 * @method add
	 * @param {string} method 'GET' or 'POST', depending on whet is needed.
	 * @param {string} name Sub-query name.
	 * @param {Object} data Data to send.
	 * @param {Function} [success] Callback on success. Called with the returned data.
	 * @param {Function} [error] Callback on error. Called with error name.
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
	
	/** Check whether automatic sending is on or off.
	 * @private
	 * @method isAutomaticOn
	 */
	function isAutomaticOn()
	{
		return interval !== 0 ;
	}
	
	/** Stop automatic sending of queries.
	 * @method stop
	 */
	window.ajax.stop = function ()
	{
		if ( isAutomaticOn() )
		{
			clearInterval( interval ) ;
			interval = 0 ;
		}
	} ;
	
	/** Start automatic sending of queries.
	 * @method start
	 * @param {number} period Time between two sendings (seconds).
	 */
	window.ajax.start = function ( period )
	{
		this.stop() ;
		interval = setInterval( runQuery, period * 100 ) ;
	} ;
	
	/** Send a query now.
	 * @method sendNow
	 */
	window.ajax.sendNow = function ()
	{
		runQuery() ;
	} ;

/*----------------------------------------------------------------------------*/

	/** Ajax query part.
	 * @class AjaxQueryPart
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
			throw new Exception( 'AjaxQueryBadMethod' ) ;
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
		
		/** HTTP method.
		 * @property method
		 */
		method: null,
		
		/** Query part name.
		 * @property name
		 */
		name: null,
		
		/** Query data.
		 * @property data
		 */
		data: {},
		
		/** Success callback.
		 * @property {Function} success
		 */
		success: $.noop,
		
		/** Error callback.
		 * @property {Function} error
		 */
		error: function ( errName )
		{
			if ( console && $.isFunction( console.error ) )
			{
				console.error( 'Error on query part “' + this.name + '”: ' + errName ) ;
			}
		}
		
	} ;

} ) ( jQuery ) ;
