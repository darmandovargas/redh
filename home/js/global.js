$(function(){
	//$('#search2').fancyInput();
	$('#directions-wrapper').perfectScrollbar();
	$('.floating-container').perfectScrollbar();

	
	$(".block-wrapper").bind("mousewheel",function(ev, delta) {
    	var scrollTop = $(this).scrollTop();
    	$(this).scrollTop(scrollTop-Math.round(delta*30));
	});

	$('#search_directions').bind('submit',function(){
		calc_route();
		return false;
    });
	$('#search').bind('focus',function(){
		$('#directions-panel').fadeOut('slow');
		return false;
    });
    $('#search-btn').bind('click',function(){
		$("#search_directions").submit(); 
		return false;
    });
    //close the directions box
    $('#directions-panel .close').bind('click',function(){
    	$('#directions-panel').fadeOut('slow');
    }); 


    //this toggles the inline content boxes
    $('.floating-box').bind('click',function(){
    	$('.floating-search').fadeOut('slow');
    	$('.floating-wrapper').queue("fx");
    	

		var el = $(this);
    	if($('.floating-wrapper').position().top > 0) {
			$('.floating-wrapper').animate(
				{ top: -600 },
				300,
				function() {
	    			// Animation complete.
	    			$('.floating-wrapper h3').html( el.find('h3').text() );
					$('.floating-wrapper p:eq(0)').html( el.data('title') );
					
					$('.floating-content').html($('#section-' + el.data('section') + ' .section-content').html());
	    		}

			);
		} else {
			$('.floating-wrapper h3').html( el.find('h3').text() );
			$('.floating-wrapper p:eq(0)').html( el.data('title') );

			$('.floating-content').html($('#section-' + el.data('section') + ' .section-content').html());
		}
		
		$('.floating-wrapper').show().animate(
			{ top: 30 },
			600
		);



		return false;
    });     
    //close the wrapper
    $('.floating-wrapper .close').bind('click',function(){
    	if($('.floating-wrapper').is(":visible")) {
			$('.floating-wrapper').animate({ top: -600 }, {duration: 1000 });
			$('.floating-search').fadeIn('slow');
		}
		return false;
    }); 

    //if the map is clicked then make sure the floating-wrapper is hidden
    /*
    $('#map_canvas').bind('click',function(){
    	if($('.floating-wrapper').is(":visible")) {
			$('.floating-wrapper').animate({ top: -600 }, {duration: 1000 });
			$('.floating-search').fadeIn('slow');
		}
		return false;
    });
	initialize_map();
	*/
});