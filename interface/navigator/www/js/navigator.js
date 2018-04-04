function loadCurrentAction( actionId, callback )
{
	var actionContainer = $(parent.Title.document.getElementById('gtc-active-action-container'));
	actionContainer.load( '?action=action!current&actionId='+actionId, function() {
		callback();
	});  
}

$(document).ready( function() {
	  
    $(document).on( 'mouseenter', '.gtc-action-row', function() {
    	var row = $(this).closest('.gtc-action-result-list-element');
    	$('#gtc-action-tree-container').find( ".gtc-action-actions" ).each( function() {
    		$(this).hide();
    	});
		var ga = row.children(".gtc-action-row");
		var gaa = ga.find(".gtc-action-actions");
		gaa.show();
		return false;
	});
    
    $(document).on( 'click', '.gtc-action-actions .btn-group', function() {
    	// filter the actions
    	var actionButton = $(this);
    	var myTree = $(this).closest(".gtc-action-result-list-element");
    	var numSubTrees = myTree.find(".gtc-action-tree").length;
    	if ( numSubTrees > 0 ) {
    		actionButton.find('.gtc-add-sub-action').each( function() {
    			$(this).hide();
    		});
    	}
    });
	
	$(document).on( 'mouseleave', '.gtc-action-result-list-element', function() {
		var row = $(this);
    	var gaa = row.find( ".gtc-action-actions" );
    	gaa.hide();
		return false;
	});

	
	$(document).on( 'click', '.gtc-note-add', function( e ) {
		e.preventDefault();
		var button = $(this);
		var actionId = button.attr('data-action-id');
		var caseId = button.attr('data-case-id');
		
		var data = { actionId: actionId };
		dlgopen( '?action=action!note&actionId='+actionId+'&caseId='+caseId,
				   '_blank', 720, 420);
	});
	
	$(document).on( 'click', '.gtc-state-change', function( e ) {
		e.preventDefault();
		var button = $(this);
		var actionId = button.attr('data-action-id');
		var stateId = button.attr('data-state-id');
		
		var data = { actionId: actionId, stateId: stateId };
		$.post( '?action=action!setstate', data, function( response ) {
			$('#gtc-label-action-'+actionId).text( button.text() );
		});
	});

	//top.window.parent
//	$(top.frames["RTop"].document).on( 'click', '.gtc-progress-button', gtcProgressButtonHandler );
//	$(top.frames["Title"].document).on( 'click', '.gtc-progress-button', gtcProgressButtonHandler );
//	$(top.frames["RBot"].document).on( 'click', '.gtc-progress-button', gtcProgressButtonHandler );
    $(document).on( 'click', '.gtc-progress-button', function ( e ) {
		e.preventDefault();
    	// do a post to store new status
		var button = $(this);
		button.button( 'loading' );
		var row = button.closest('.gtc-action-row');
		var actionSummary = row.find('.gtc-action-summary');
    	var actionId = row.attr('data-action-id');
    	var progressIndicator = row.find('.gtc-action-status');
    	var status = button.attr('data-value');
    	var data = { actionId: actionId, status: status };
    	$.post( '?action=action!setstatus', data, function( response ) {
    		button.button( 'reset' );
    		if ( status == 'start-progress' ) {
    			// we are started.
    			button.attr( 'data-value', 'stop-progress' );
    			button.addClass( 'active' );
    			button.text( 'Stop Progress' );
    			progressIndicator.show();
    			actionSummary.addClass('active');
    			document.location.href = "?action=action!work&actionId="+actionId;
    		} else if ( status == 'stop-progress' ) {
    			// we are stopped.
    			button.attr( 'data-value', 'start-progress' );
    			button.removeClass( 'active' );
    			button.text( 'Start Progress' );
    			progressIndicator.hide();
    			actionSummary.removeClass('active');
    		}
			//alert( "action status updated" );
		});
	});
    
    $(document).on( 'click', '.gtc-action-summary.active', function ( e ) {
    	var row = $(this).closest('.gtc-action-row');
    	var actionId = row.attr('data-action-id');
    	document.location.href = "?action=action!work&actionId="+actionId;
    });
});
