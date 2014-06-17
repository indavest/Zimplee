jQuery(function(){
	jQuery(window).on('resize',function(){	
		if($(document).viewport.getWidth() >= 984){
			//console.log("window resized: "+$(document).viewport.getWidth()+"; Showing Desktop");
			jQuery(".fullwidthbanner-container").show();
			jQuery(".homepage-signup").show();
			jQuery(".fullwidthbanner-container").css("max-width","725px");
		}else{
			//console.log("window resized: "+$(document).viewport.getWidth()+"; Showing Mobile");
			jQuery(".fullwidthbanner-container").show();
			jQuery(".homepage-signup").hide();
			jQuery(".fullwidthbanner-container").css("max-width","980px");
		}
	});
	jQuery(window).trigger('resize');
});