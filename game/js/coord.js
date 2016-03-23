	setInterval(function() {
		var elem = getComputedStyle(document.getElementById("map"), null);
		var left = elem.getPropertyValue("left");
		left = left.replace("-","");
		left = left.replace("px","");

		var height = elem.getPropertyValue('top');
		height = height.replace("-","");
		height = height.replace("px","");
			
		//Move X
		$('#container_xcoord').scrollLeft(left);
		//Move Y
		$('#container_ycoord').scrollTop(height);		
		
	}, 1);
	
	//Cursor Map
	
	function handleMouseUp(e, zone){
		$(zone).unbind("mousemove");
		$(zone).css("cursor","");
	}
	
	function handleMouseDown(e, zone){
		
		if(e.button == 0){
			$(zone).bind("mousemove", function(e){
				$(zone).css("cursor","move");
			});
		}
	}	