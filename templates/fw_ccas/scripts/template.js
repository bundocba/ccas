/* Javscript Document  */
jQuery(document).ready(function() {
	jQuery("ul").each(function(){ 
		jQuery(this).find("li:last").addClass("end");	
	});
	jQuery("#topmenu").find("li:last").css({background: "none"});		
	jQuery("#topmenu ul li a").hover(function() {
		jQuery(this).parent().find("ul.subnav1").slideDown("slow");
		jQuery(this).parent().hover(function() {}, function(){
		jQuery(this).parent().find("ul.subnav1").slideUp("slow");
		});
	});
	jQuery("#topmenu .joomla-nav li").hover(function(){
		jQuery(this).find("ul:first").css({visibility: "visible",display: "none"}).fadeIn("slow");
		jQuery(this).parent().prev().addClass("active");
	/* -- n?u b? display none thì khi hover l?i l?n th? 2 thì kg có faceIn --  */
	},function(){
		jQuery(this).find("ul:first").css({visibility: "hidden"});
		jQuery(this).parent().prev().removeClass("active");
	});
	jQuery("#topmenu .joomla-nav li ul li a").each(function(){
		if(this.href == document.location.href){
			jQuery(this).parent().parent().prev().addClass("active");
		}
	});
});
