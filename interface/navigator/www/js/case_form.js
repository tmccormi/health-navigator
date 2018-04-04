var case_form = function( options ) {

	var mode = options.mode;
	var count = options.actionCount;
	
	var init = function()
	{
		$('ul.tree').toggle();
	    $('.tree-toggler').click( function() {
	        $(this).parent().parent().children('ul.tree').toggle( 300 );
	    });
	
	    var groupSelectVal = $('#gtc-group-select').val();
	    if ( groupSelectVal == '' ) {
	    	$('#gtc-user-element').hide();
	    }
	    
	    $('#gtc-group-select').change( function() {
	        if ( $(this).val() ) {
	            $('#gtc-user-element').show();
	        } else {
	        	$('#gtc-user-element').hide();
	        }
	    });
	
	    $(document).on( 'click', '.gtc-action-show-mini-form', function( e ) {
	        e.preventDefault();
	    	$(this).closest(".gtc-action-show-mini-form-container").find('.form-toggle').toggle( 300 );
	    });
	    
	    $(document).on( 'change', '.gtc-action-type-select', function( e ) { 
	    	$(this).parent().find( '.selected-action-type-name' ).val( $("option:selected", this).text() );
	    });
	
	    $(document).on( 'click', '.gtc-action-mini-form .cancel', function( e ) {
	    	e.preventDefault();
	    	$(this).closest(".gtc-action-mini-form").find( "input[type=text], textarea, select" ).val( "" );
	    	// If there are children, toggle the form; otherwise remove the tree branch, and replace the "add sub-action" button 
	    	var myTree = $(this).closest(".gtc-action-tree");
	    	var numResultElements = myTree.find(".gtc-action-result-list-element").length;
	    	if ( numResultElements ) {
	    		$(this).closest(".gtc-action-show-mini-form-container").find('.form-toggle').toggle( 300 );
	    	} else {
	    		myTree.remove();
	    	}
	    });
	
	    if ( mode == 'create' ) {
		    count = 1; 
		    var tree = { parentId: 0, actionId: 0, parentKey: 0, actionKey: count };
		    $("#actionTreeTemplate").tmpl( tree ).appendTo( "#gtc-action-tree-container" );
		    count++;
		}
	
	    $(document).on( 'click', '.gtc-add-action-button', function( e ) {
	        e.preventDefault();
	        var myButton = $(this);
	        var action = {};
	        var miniform = myButton.closest( ".gtc-action-miniform" );
	        $(miniform).find( ".gtc-action-miniform-param" ).each( function() {
	            var formElement = $(this);
	            var name = formElement.attr( "data-name" );  
	            var value = formElement.val(); 
	            action[name] = value;
	        });
	        // hide the miniform
	        myButton.closest(".gtc-action-show-mini-form-container").children('.form-toggle').toggle( 300 );
	        // Render the new action using the template, insert before the miniform
	        var miniformLi = myButton.closest(".gtc-action-miniform-list-element");
	        $("#actionRowTemplate").tmpl( action ).insertBefore( miniformLi );
	        // copy the miniform, and stick it inside the hidden area of the new action row
	        miniform.formhtml();
	        var miniformClone = miniform.clone();
	        $("#gtc-action-row-"+action['actionKey']).find('.gtc-hidden-miniform').append( miniformClone );
	        
	        var actionTreeElement = $(this).closest(".gtc-action-tree");
	        var parentKey = 0; 
	        var parentId = 0;
	        if ( typeof actionTreeElement !== "undefined" ) {
	        	parentKey = actionTreeElement.attr( 'data-parent-key' );
	        	parentId = actionTreeElement.attr( 'data-parent-id' );
	        }
	        var params = { parentId: parentId, actionId: 0, parentKey: parentKey, actionKey: count };
	        var actionListElement = myButton.closest(".gtc-action-list-element");
	        var myTree = myButton.closest(".gtc-action-tree");
	        $("#actionMiniformTemplate").tmpl( params ).appendTo( myTree );
	        
	        // toggle the newly created miniform
	        myTree.children('.gtc-action-miniform-list-element').find('.form-toggle').toggle();
	        miniformLi.remove();
	        count++;
	        return false;
	    });
	
	    $(document).on( 'click', '.gtc-delete-action', function( e ) {
	        e.preventDefault();
	        $(this).closest( ".gtc-action-list-element" ).fadeOut( 300 ).remove();
	        return false;
	    });
	
	    $(document).on( 'click', '.gtc-add-sub-action', function( e ) {
	        e.preventDefault();
	    	var actionListElement = $(this).closest(".gtc-action-result-list-element");
	        var actionElement = $(this).closest(".gtc-action-row");
	        var parentId = actionElement.find(".gtc-action").attr( 'data-action-id' );
	        var parentKey = actionElement.find(".gtc-action").attr( 'data-action-key' );
	        var tree = { parentId: parentId, actionId: 0, parentKey: parentKey, actionKey: count };
	        $("#actionTreeTemplate").tmpl( tree ).appendTo( actionListElement );
	        count++;
	        return false;
	    });
	    
	    $("#gtc-group-select").change( function() {
	    	var value = $(this).val();
	    	$.post( '?action=case!useroptions', { group: value }, function( response ) {
	    		$("#gtc-user-options-container").text( '' );
	    		$("#gtc-user-options-container").html( response );
			});
	    });
	    
	    $("#gtc-case-submit-button").click( function( e ) {
	    	e.preventDefault();
	    	// gather actions
	    	var data = {};
	    	data['clientId'] = $("#gtc-client-element").val();
	    	data['creatorId'] = $("#gtc-creator-element").val();
	    	data['ownerId'] = $("#gtc-owner-element").val();
	    	data['caseId'] = $("#gtc-case-id-element").val();
	    	data['group'] = $("#gtc-group-select").val();
			data['userId'] = $("#gtc-user-select").val();
	    	var actions = [];
	    	$("#gtc-case-create-form").find(".gtc-action-row").each( function() {
	    		var actionRow = $(this);
	    		var action = { actionId: actionRow.attr("data-action-id") };
	    		actionRow.find( ".gtc-action-miniform-param" ).each( function() {
	    			var element = $(this);
	    			var name = element.attr( "data-name" );
	    			var value = element.val();
	    			action[name] = value;
	    		});
	    		actions.push( action );
	    	});
	    	
	    	data['actions'] = actions;
	    	
			$.post( '?action=case!save', data, function( response ) {
				alert( "saved" );
			});
			return false;
	    });
	}
    
    return {
        init: function() {
            $(document).ready( function() {
                init();
            });
        }
    };
}