/**
 * 
 */

function resources_input(name, limit){

	$("#"+name).keypress(function (e) {
		
	     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	        return false;
	    }else{
	    	
	    	$("#"+name).on("keyup change mouseup", function() {

	    		var act_val = $(this).val();
	    	
	    		document.getElementById("range_"+name).value = act_val;
	    	
	    		if(act_val > limit){
	    			$("#"+name).css("background-color", "red");
	    		}else{
	    			$("#"+name).css("background-color", "white");
	    		}

	    	});
	    }
	     
	   });

	return true;
}

function clean_text(id, default_text){
	
	$("#"+id).on("mouseup mousedown", function() {

		if($("#"+id).val() == default_text){
			$("#"+id).val("");
		}
	});

	
	return true;
}

function input_autocomplete(id, length){
	
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

    $("#" + id).on( "keyup", function( event ) {
        // don't navigate away from the field on tab when selecting an item
        if ( event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      }).autocomplete({
    	  minLength: length,
    	  source: 	function( request, response ) 
    	  			{
			    		$.ajax({
			    			  type: "GET",
			    			  url: "./modules/game/map/search.php",
			    			  dataType: "json",
			    			  data: {term: request.term},
			    			  success: function (data) {
		                            response($.map(data, function (item) {
		                            	return {
		                                    label: item.label,
		                                    value: item.value
		                                }
		                            }));
		                       }
			    		});
    	  			}
      });
  }

//Refresh max value to a range input

function range_max(id_input, new_max){
	$("#"+id_input).attr("max", new_max);
}