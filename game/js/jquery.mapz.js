/**
*	jQuery Mapz v1.0
*
*	by Danny van Kooten - http://dannyvankooten.com
*	Last Modification: 20/06/2011
*
*	For more information, visit:
*	http://dannyvankooten.com/jquery-plugins/mapz/
*
*	Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
*		- Free for use in both personal and commercial projects
*		- Attribution requires leaving author name, author link, and the license info intact.	
*/

(function( $ ){
			
  $.fn.mapz = function(options) {

		var settings = {
			'zoom'			:	false,
			'createmaps' 	:	false,
			'mousewheel' 	: 	false
		};

		 if ( options ) { 
			$.extend( settings, options );
		}

		var viewport 	= this.parent('.map-viewport');
		var map 		= this;
		var constraint 	= $(document.createElement('div')).addClass('mapz-constraint').css('position','absolute').appendTo(viewport);
		
		// Add current-level class to first map level
		map.children(".level:first").addClass('current-level');
		
		// Create constraint for current level.
		createConstraint();

		map.draggable({
			 containment : constraint
		});
		
		map.bind( "mousedown", function() {
			map.css('cursor','move');
		});
		
		map.bind( "mouseup", function() {
			map.css('cursor','default');
		});
		
		map.bind( "wheel", onwheel);

		
		function createMaps(){
			
			var htmlmap = viewport.children('map');
			var scale = 1;
			var i = 0;
			
			// Loop trough zoom levels
			map.children('.level').each(function() {
				i++;
				
				// If current map level, return. This one should have a map already.
				if($(this).hasClass('current-level')) return;
				
				// Get scales for map to create
				scale = $(this).width() / map.width();
				
				// Create new map element
				var newmap = $(document.createElement('map')).attr('name',map.attr('id') + '-map-' + i);
				
				// Calculate new coords for each area element
				htmlmap.children('area').each(function() {
					var newArea = $(this).clone();
					
					var coords = $(this).attr('coords').split(',');
					
					for(c in coords) {
						coords[c] = Math.ceil(coords[c] * scale);
					}
					
					newArea.attr('coords',coords).appendTo(newmap);
				});
				
				// Append new map to viewport and hook to zoom level
				newmap.appendTo(viewport);
				$(this).attr('usemap','#' + map.attr('id') + '-map-' + i);
			});
		}

		// Create a constraint div so map can't be dragged out of view.
		function createConstraint()
		{
			constraint.css({
				left : -(map.width()) + viewport.width(),
				top : -(map.height()) + viewport.height(),
				width : 2 * map.width() - viewport.width(),
				height : 2 * map.height() - viewport.height()
			});
			
			// Check if map is currently out of bounds, revert to closest position if so
			if(map.position().left < constraint.position().left) map.css('left',constraint.position().left);
			if(map.position().top < constraint.position().top) map.css('top',constraint.position().top);
		}
	 };
})( jQuery );
