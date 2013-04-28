$(document).ready(function() { 
	$("a.group").fancybox(); 
	$("a.video-popup").fancybox({'width':536,'height':514, 'type':'iframe'}); 
	$("a.friend-popup").fancybox({'width':536,'height':514, 'type':'iframe'}); 
	$("a.testimonial-popup").fancybox({'width':803,'height':420, 'type':'iframe'}); 
    
    $('.hiw_side_1').show().css({opacity:'1'});
	$('.hiw_right_1').show().css({opacity:'1'});
    $('#hiw_nav a').click( function(){
       $('#hiw_nav a').removeClass('active');
       var idx = $(this).attr('rel');
       $('#belt').animate({marginLeft: (575-(idx * 575))+'px'},600); 
       $('.hiw_side:visible').animate({opacity:'0'},400,function(){ 
           $(this).hide(); 
           $('.hiw_side_' + idx).show().animate({opacity:'1'},800);
       });       
       for (i = 1; i <= idx; i++) {
           $('#hiw_'+i).addClass('active');
       }
    });
    
    $('#tab_1').siblings().hide();
    $('#tabs li a').click(function(){
       var idx = $(this).attr('rel');
       $('#tabs li').removeClass('active');
       $(this).parent().addClass('active');
       $('#tab_'+idx).fadeIn(500).siblings().hide();
    });
    
}); 

var timeout    = 500;
var closetimer = 0;
var ddmenuitem = 0;

function jsddm_open()
{  jsddm_canceltimer();
   jsddm_close();
   ddmenuitem = $(this).find('ul').css('visibility', 'visible');
   $(this).find('#nav5').addClass('show');
   $(this).find('#nav3').addClass('show');
   $(this).find('#nav6').addClass('show');
}

function jsddm_close() {  
	if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');
	$('#nav5').removeClass('show');
	$('#nav3').removeClass('show');
	$('#nav6').removeClass('show');
}

function jsddm_timer()
{  closetimer = window.setTimeout(jsddm_close, timeout);}

function jsddm_canceltimer()
{  if(closetimer)
   {  window.clearTimeout(closetimer);
      closetimer = null;}
}

$(document).ready(function()
{  $('#jsddm > li').bind('mouseover', jsddm_open)
   $('#jsddm > li').bind('mouseout',  jsddm_timer)});

document.onclick = jsddm_close;

function faq_click (id) {
	
	var title=document.getElementById('question' + id);
	var desc=document.getElementById('answer' + id);
	
	if (desc.style.display=='block') {
		title.style.background='url(images/faq-close.gif) no-repeat left top';
		desc.style.display='none';
	} else {
		
		title.style.background='url(images/faq-open.gif) no-repeat left top';
		desc.style.display='block';
	}
	
}