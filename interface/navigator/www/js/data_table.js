var data_table = function( options ) {
	
	var tableId = options.tableId;
	var resultsUrl = options.resultsUrl; // Url to fetch results
	var columnHeadersJSON = options.columnHeadersJSON; // JSON array of header titles
	var iDisplayLength = options.iDisplayLength; // integer items per page
	
	// pagination strings
	var sFirst = options.sFirst;
    var sPrevious = options.sPrevious;
    var sNext = options.sNext;
    var sLast = options.sLast;
    
    var oTable;
    	
    var fn_work = function() 
    {
    	 // Initializing the DataTable.
    	 //
    	 oTable = $('#'+tableId).dataTable( {
    	  "bProcessing": true,
    	  // next 2 lines invoke server side processing
    	  "bServerSide": true,
    	  "sAjaxSource": resultsUrl,
    	  // sDom invokes ColReorderWithResize and allows inclusion of a
			// custom div
    	  //"sDom"       : 'Rlfrt<"mytopdiv">ip',
    	  // These column names come over as $_GET['sColumns'], a
			// comma-separated list of the names.
    	  // See: http://datatables.net/usage/columns and
    	  // http://datatables.net/release-datatables/extras/ColReorder/server_side.html
    	  "aoColumns": columnHeadersJSON,
    	  "aLengthMenu": [ 10, 25, 50, 100 ],
    	  "iDisplayLength": iDisplayLength,
    	  // language strings are included so we can translate them
    	  "oLanguage": {
    	   "sSearch"      : "Search all columns",
    	   "sLengthMenu"  : "Show _MENU_ entries",
    	   "sZeroRecords" : "No matching records found",
    	   "sInfo"        : "Showing _START_ to _END_ of _TOTAL_ entries",
    	   "sInfoEmpty"   : "Nothing to show",
    	   "sInfoFiltered": "(filtered from _MAX_ total entries)",
    	   "oPaginate": {
    	    "sFirst"   : sFirst,
    	    "sPrevious": sPrevious,
    	    "sNext"    : sNext,
    	    "sLast"    : sLast
    	   }
    	  }
    	 });
    }

    var fn_wire_events = function() {
    	$(".column_behavior_details").live( 'click', function( e ) {
    		e.preventDefault();
    		e.stopPropagation();

    		var nTr = this.parentNode.parentNode;
    		if ( $(this).text() == 'Hide' ) {
    			/* This row is already open - close it */
    			oTable.fnClose( nTr );
    			$(this).text( "Show" );
    		} else {
    			/* Open this row */
    			$(this).text( "Hide" );
    			var requestUrl = $(this).attr( "href" );
    			$.get( requestUrl, function( data ) {
    				oTable.fnOpen( nTr, data, 'details' );
    	    	});
    		}
    	});
    }

    return {
        init: function() {
            $( document ).ready( function() {
                fn_wire_events();
                fn_work();
            });
        }
    };
}
