/**
 * Easing equation, borrowed from jQuery easing plugin
 * http://gsgd.co.uk/sandbox/jquery/easing/
 */ 
jQuery.easing.easeOutQuart = function (x, t, b, c, d) {
	return -c * ((t=t/d-1)*t*t*t - 1) + b;
};

/**
 * Setup selection variables
 */
 
var colorideasSwatch1 = '';
var colorideasSwatch2 = '';
var colorideasSwatch3 = '';

/**
 * Setup state variables
 */
 
var swatch2visible = false;
var swatch3visible = false;

/**
 * Index variable for use during processing
 */
var idx = '';

/**
 * Setup sliding panels and other animations
 */
  
$(document).ready(function(){
						   
    $('#wall').css('cursor','pointer').click(function(){
        $(this).animate({'opacity':1});
    });
                           
	/**
	 * Update current selection for carousel id, with index idx, from list list
	 */
	
	function swatch_updateSelection(idx, id, list)
	{
        $('#suggestions:visible').fadeOut(400);
        $('#wall').css('opacity' ,'.5');
        // Set selection wrapper visible, in case it was hidden
		$("#swatch-items-" + id + "-selection").css('display','block');
		// Grab current item details
		var current_item = list[idx - 1];
		// Set selection image
		$("#swatch-items-" + id + "-selection img").attr('src',current_item.url);
		// Set selection title
		$("#swatch-items-" + id + "-selection-title").html(current_item.title);	
		// Update current selection
        window['colorideasSwatch1'] = current_item.title;

        if (id == 1) {
            window.cid = current_item.cupboard_id;
            window.wid = current_item.wall_id;
            swatch2_enable();
            swatch3_enable();
        }
	}
	
	/**
	 * Item html creation helper.
	 */
	function swatchCarousel_getItemHTML(item)
	{
		return '<img src="' + item.url + '" width="110" height="110" alt="' + item.title + '" /><div>' + item.title + '</div>';
	};
	
	/**
	 * Circular carousel helper callback
	 */
	
	function swatchCarousel_itemVisibleOutCallback(carousel, item, i, state, evt)
	{
		carousel.remove(i);
	};
	
	/**
	 * Circular carousel helper callbacks - must be set individually as we can't pass variables
	 */
	
	function swatchCarousel1_itemVisibleInCallback(carousel, item, i, state, evt)
	{
		// The index() method calculates the index from a
		// given index who is out of the actual item range.
		var idx = carousel.index(i, colorideas1_itemList.length);
				
		carousel.add(i, swatchCarousel_getItemHTML(colorideas1_itemList[idx - 1]));
	};
	
	function swatchCarousel2_itemVisibleInCallback(carousel, item, i, state, evt)
	{
		// The index() method calculates the index from a
		// given index who is out of the actual item range.
		var idx = carousel.index(i, colorideas2_itemList.length);
				
		carousel.add(i, swatchCarousel_getItemHTML(colorideas2_itemList[idx - 1]));
	};
	
	function swatchCarousel3_itemVisibleInCallback(carousel, item, i, state, evt)
	{
		// The index() method calculates the index from a
		// given index who is out of the actual item range.
		var idx = carousel.index(i, colorideas2_itemList.length);
				
		carousel.add(i, swatchCarousel_getItemHTML(colorideas2_itemList[idx - 1]));
	};
	
	function swatch2_enable()
	{
		$.each(colorideas2_itemList, function(i,obj){
            if (obj.reference_id == window.cid) {
                showme2 = i+1;
                window['colorideasSwatch2'] = obj.title;
            }
        });
        
        if(swatch2visible == false) {
			$("#swatch-items-2").removeClass('colorideas-items-hidden');
			$("#swatch2 h2").removeClass('colorideas-items-hidden');
				
			$('#swatch-carousel-2').jcarousel({
				easing: 'easeOutQuart',
				animation: 450,
				scroll: 1,
				start: showme2 - 3,
				initCallback: swatchCarousel2_init,
				buttonNextHTML: null, // Don't autobuild button
				buttonPrevHTML: null, // Don't autobuild button
				wrap: 'circular', // Set circular wrap
				itemVisibleInCallback: {onBeforeAnimation: swatchCarousel2_itemVisibleInCallback},
				itemVisibleOutCallback: {onAfterAnimation: swatchCarousel_itemVisibleOutCallback}
			});	
			swatch2visible = true;
		} else {                        
            swatch_updateSelection(showme2, 2, colorideas2_itemList);
        }
	}
	
	function swatch3_enable()
	{
		$.each(colorideas2_itemList, function(i,obj){
            if (obj.reference_id == window.wid) {
                showme3 = i+1;
                window['colorideasSwatch3'] = obj.title;
            }
        });
        
        if(swatch3visible == false) {
            $("#swatch3").css({opacity: .3}).click(function(){
                $(this).animate({opacity: 1},500);
                $(this).unbind('click');
            });
			$("#swatch-items-3").removeClass('colorideas-items-hidden');
			$("#swatch3 h2").removeClass('colorideas-items-hidden');	
            
			$('#swatch-carousel-3').jcarousel({
				easing: 'easeOutQuart',
				animation: 450,
				scroll: 1,
				start: showme3 - 3,
				initCallback: swatchCarousel3_init,
				buttonNextHTML: null, // Don't autobuild button
				buttonPrevHTML: null, // Don't autobuild button
				wrap: 'circular', // Set circular wrap
				itemVisibleInCallback: {onBeforeAnimation: swatchCarousel3_itemVisibleInCallback},
				itemVisibleOutCallback: {onAfterAnimation: swatchCarousel_itemVisibleOutCallback}
			});	
			swatch3visible = true;
			
		} else {                       
            swatch_updateSelection(showme3, 3, colorideas2_itemList);
		}
	}

	/**
	 * Init callback for carousel 1
	 */
	
	function swatchCarousel1_init(carousel) {
	
		/**
		 * Bind handlers for next / previous buttons
		 */
		
		jQuery('#swatch-next-1').bind('click', function() {
			carousel.next();		
			idx = carousel.index(carousel.first+3, colorideas1_itemList.length);
			swatch_updateSelection(idx, 1, colorideas1_itemList);
			// Enable swatch 2 carousel, if hidden
			//swatch2_enable();
			return false;
		});
	
		jQuery('#swatch-prev-1').bind('click', function() {
			carousel.prev();
			idx = carousel.index(carousel.first+3, colorideas1_itemList.length);
			swatch_updateSelection(idx, 1, colorideas1_itemList);
			// Enable swatch 2 carousel, if hidden
			swatch2_enable();
			return false;
		});
		
		/**
		 * Bind click function to scroll to clicked element, update selection
		 */
		 
		$("#swatch-carousel-1").delegate("li", "click", function(){
			idx = $(this).attr('jcarouselindex'); 
			carousel.scroll(idx-3);
			idx = carousel.index(idx, colorideas1_itemList.length);
			swatch_updateSelection(idx, 1, colorideas1_itemList);
			// Enable swatch 2 carousel, if hidden
			swatch2_enable();
		});
		
		$("#swatch-carousel-1").delegate("li", "hover", function(){
			$(this).toggleClass("hover");														 
		});
		
	};
	
	/**
	 * Init callback for carousel 2
	 */
	
	function swatchCarousel2_init(carousel) {
	
		/**
		 * Bind handlers for next / previous buttons
		 */
		
		jQuery('#swatch-next-2').bind('click', function() {
			carousel.next();		
			idx = carousel.index(carousel.first+3, colorideas2_itemList.length);
			swatch_updateSelection(idx, 2, colorideas2_itemList);
			return false;
		});
	
		jQuery('#swatch-prev-2').bind('click', function() {
			carousel.prev();
			idx = carousel.index(carousel.first+3, colorideas2_itemList.length);
			swatch_updateSelection(idx, 2, colorideas2_itemList);
			return false;
		});
		
		/**
		 * Bind click function to scroll to clicked element, update selection
		 */
		 
		$("#swatch-carousel-2").delegate("li", "click", function(){
			idx = $(this).attr('jcarouselindex'); 
			carousel.scroll(idx-3);
			idx = carousel.index(idx, colorideas2_itemList.length);
			swatch_updateSelection(idx, 2, colorideas2_itemList);
		});
		
		$("#swatch-carousel-2").delegate("li", "hover", function(){
			$(this).toggleClass("hover");														 
		});
        

        swatch_updateSelection(idx, 2, colorideas2_itemList);
        
		
	};
	
	/**
	 * Init callback for carousel 3
	 */
	
	function swatchCarousel3_init(carousel) {
	
		/**
		 * Bind handlers for next / previous buttons
		 */
		
		jQuery('#swatch-next-3').bind('click', function() {
			carousel.next();		
			idx = carousel.index(carousel.first+3, colorideas2_itemList.length);
			swatch_updateSelection(idx, 3, colorideas2_itemList);
			return false;
		});
	
		jQuery('#swatch-prev-3').bind('click', function() {
			carousel.prev();
			idx = carousel.index(carousel.first+3, colorideas2_itemList.length);
			swatch_updateSelection(idx, 3, colorideas2_itemList);
			return false;
		});
		
		/**
		 * Bind click function to scroll to clicked element, update selection
		 */
		 
		$("#swatch-carousel-3").delegate("li", "click", function(){
			idx = $(this).attr('jcarouselindex'); 
			carousel.scroll(idx-3);
			idx = carousel.index(idx, colorideas2_itemList.length);
			swatch_updateSelection(idx, 3, colorideas2_itemList);
		});
				
		$("#swatch-carousel-3").delegate("li", "hover", function(){
			$(this).toggleClass("hover");														 
		});
        
        swatch_updateSelection(window.wid, 3, colorideas2_itemList);
        
        
	};
	
	

	
	$('#swatch-carousel-1').jcarousel({
		easing: 'easeOutQuart',
		animation: 450,
		scroll: 1,
		start: 1,
		initCallback: swatchCarousel1_init,
        buttonNextHTML: null, // Don't autobuild button
        buttonPrevHTML: null, // Don't autobuild button
		wrap: 'circular', // Set circular wrap
        itemVisibleInCallback: {onBeforeAnimation: swatchCarousel1_itemVisibleInCallback},
        itemVisibleOutCallback: {onAfterAnimation: swatchCarousel_itemVisibleOutCallback}
	});
	
	/*$("#swatch3 h2").bind('click',function(){
		swatch3_enable();							   
	});*/
	
	/**
	 * Bind fancybox popup for email form
	 */
	 
	$("#colorideas-email-popup a").bind('click',function(){
		
		if(colorideasSwatch1 != '' && colorideasSwatch2 != '') {
			
			// Clear message
			$("#colorideas-email-message").html('');	
		
			// Show loading animation
			$.fancybox.showActivity();
			
			// Display fancybox
			$.fancybox({
				'href': 'lightbox.php?iframe&pg=colorideas&swatch1='+colorideasSwatch1+'&swatch2='+colorideasSwatch2+'&swatch3'+colorideasSwatch3,
				'type':'iframe',
				'width':536,
				'height':514
			});
		
		} else {
			// Set message
			$("#colorideas-email-message").html('Please select a benchtop and an additional color.');	
		}
		
		return false;									 
														 
		//.fancybox({'frameWidth':536,'frameHeight':514}); 												 
	});
	
						   
});