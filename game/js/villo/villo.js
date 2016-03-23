/*
 *	Refresh data 
 */

function refresh_resource(id, max){

	var qnt = $("#v"+id).text();
	
	if(qnt < max){
		qnt++;
		$("#v"+id).text(qnt);
	}

	$("#"+id).val(qnt).trigger("change");
	
	return true;
}


//Refresh countdown
$('*#countdown').each(function(e) {

	var element = $(this);

	setInterval(function(){

	    var time  = element.text();
	    var empty = /^\s*$/.test(time);

	    if(!empty){
	    	

		    var splitting_c1 = time.split("-");
		    
		    if(splitting_c1.length == 1){
		    	
		    	text_msg = time.split(" ");
		    	time = text_msg[text_msg.length - 1];
			    var splitting_h	= time.split(":"); 
	
			    var h = splitting_h[0];
			    var m = splitting_h[1];
			    var s = splitting_h[2];

			    if(s > 0){
			    	s--;
			    	
			    	if(s <= 9){
			    		s = "0" + s;
			    	}
			    	
			    }else{
			    	
			    	if(m > 0){
			    		
				    	s = 59;
				    	m--;
				    	
				    	if(m <= 9){
				    		m = "0" + m;
				    	}
				    	
			    	}else{
			    		
			    		if(h > 0){
			    			s = 59;
			    			m = 59;
			    			h--;
			    			
			    			if(h <= 9){
			    				h = "0" + h;	    				
			    			}	    			
			    		}
			    	}
			    }
			    
			    if(!isNaN(h) && !isNaN(m) && !isNaN(s)){
				    //Text before time
				    var txt = "";
				    
				    for(var x = 0; x < text_msg.length - 1; x++){
				    	txt = txt + " " + text_msg[x];
				    }
				    
			    	element.html(txt + " " + h + ":" + m + ":" + s);
			    	
				    if(h == "00" && m == "00" && s == "00"){
				    	element.remove();
				    }
			    }
		    }
	    
	    }
	    
	},1000);
    
});