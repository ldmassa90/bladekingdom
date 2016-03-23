/**
 *
 */

 function down_resources(qt1, qt2, qt3, qt4, base_id){
	 
	 var down_speed = 5;
	 
	 var down_qt1 = setInterval(
			 					function(){ 
			 						$("#"+base_id+"1").html($("#"+base_id+"1").text() - 1);
			 						qt1--;
			 						
			 						if(qt1 == 0){
			 							clearInterval(down_qt1);
			 						}
			 						 
			 					}, down_speed);

	 var down_qt2 = setInterval(
				function(){ 
					$("#"+base_id+"2").html($("#"+base_id+"2").text() - 1);
					qt2--;
					
					 if(qt2 == 0){
						 clearInterval(down_qt2);
					 }
					 
				}, down_speed);
	 
	 var down_qt3 = setInterval(
				function(){ 
					$("#"+base_id+"3").html($("#"+base_id+"3").text() - 1);
					qt3--;
					
					if(qt3 == 0){
						 clearInterval(down_qt3);
					}
					 
				}, down_speed);
	 
	 var down_qt4 = setInterval(
				function(){ 
					$("#"+base_id+"4").html($("#"+base_id+"4").text() - 1);
					qt4--;	 
					
					if(qt4 == 0){
						clearInterval(down_qt4);
					}
				}, down_speed);
	 
}